###############################################################################
# Mapathon Team MAPM ----
# Find the right place
# 
# 26.11.2019
# Markus
###############################################################################
# How to use: ----
# Put all OSM-files and noisemaps in the same folder as this script 
# (maybe make new project if necessary)
# then check the parameters and load in
# ...profit?
###############################################################################
library(sf)

library(scales)

library(doParallel)
# windows needs snow: (comment out doParallel for that, also within the script, 
# line 118-119, 178-179)
#library(parallel)
#library(doSNOW)
#detectCores()
#NumberOfCluster <- detectCores()/2
# how many jobs you want the computer to run at the same time
#cl <- makeCluster(NumberOfCluster) # Make clusters 

# PARAMETER SETUP ----
# the name for the resulting file:
outname = "findtherightplace"

# define input osm files, if they are clipped or not:
list = list.files(pattern = "clip_.+shp$", full.names=T) #clipped
#list = list.files(pattern = "gis_osm_.+shp$", full.names=T) #initial files
# list should contain 18 files! if not check at load data below

# search radius for the features in meter:
# eating, shopping, etc..
dist_all    = 500 
# nature
dist_nature = 1000
# bus stops
dist_onpv   = 200
# noise maps
dist_noise  = 50



# simplify loading and not forgetting to transform again ----
load_n_tansform <- function(filename) {
  return(st_transform(st_read(filename), 25832))
}

# load data ----
buildings = load_n_tansform(list[1])              # ..buildings_a_..
landuse = load_n_tansform(list[2])                # ..landuse_a_..
city = load_n_tansform(list[5])                   # ..places_a_..
pois_area = load_n_tansform(list[9])              # ..pois_a_..
pois_point = load_n_tansform(list[10])            # ..pois_..
rail = load_n_tansform(list[11])                  # ..railways_..
roads = load_n_tansform(list[12])                 # ..roads_..
transport_a = load_n_tansform(list[15])           # ..transport_a_..
transport = load_n_tansform(list[16])             # ..transport_..
noise = st_set_crs(st_read("noise_db.shp"), 25832)# name for the road noise map
railway = load_n_tansform('railway_noisemap.shp') # name for the rail noise map



# define places on which to produce map
cities = c('Jena')
# define residential buildings
residential = c('apartments', 'house', 'residential', NA)


# define interests ----
# get all possible values by unique(df$column)
interest_eating     = c('restaurant', 'cafe', 'fast_food')
interest_nightlife  = c('nightclub', 'pub', 'bar')
interest_culture    = c('arts_centre', 'memorial', 'attraction', 'cinema', 
                      'museum','artwork', 'community_centre', 
                      'theatre', 'monument')
interest_shopping   = c('bank', 'supermarket', 'bakery', 'clothes', 'kiosk',
                      'bookshop', 'butcher', 'convenience', 'vending_any',
                      'chemist', 'florist', 'beauty_shop', 'department_store', 
                      'shoe_shop', 'beverages', 'furniture_shop', 'jeweller', 
                      'toy_shop', 'sports_shop', 'gift_shop', 'outdoor_shop',
                      'mobile_phone_shop', 'computer_shop', 'greengrocer')
interest_university = c('university')
interest_nature     = c('park', 'playground', 'forest', 'meadow', 'grass', 
                      'recreation_ground', 'scrub')
interest_opnv       = c('bus_stop', 'tram_stop', 'railway_halt', 
                      'railway_station')

###############################################################################
# 
###############################################################################

# get only residential buildings and define city if needed
residential_buildings = st_intersection(buildings[buildings$type %in% 
                                                    residential,],
                                        city[city$name %in% cities,]$geometry)

# combine pois
pois_all = rbind(pois_point, st_centroid(pois_area))

# get interests which are not covered by pois_all
pois_uni = st_centroid(st_union(buildings[buildings$type %in% 
                                            interest_university,]))
pois_nature = rbind(landuse[landuse$fclass %in% interest_nature,], 
                    pois_area[pois_area$fclass %in% interest_nature,])
pois_opnv = rbind(transport[transport$fclass %in% interest_opnv,], 
                  st_centroid(transport_a))



# loop through all buildings, get distances and densities ----
start_time = Sys.time()
registerDoParallel() #doparallel
#registerDoSNOW(cl) #dosnow
result =
  foreach(index = 1:nrow(residential_buildings), .packages = 'sf',
          .combine='rbind')%dopar%{
    pois_around_point = st_intersection(pois_all, 
                                        st_buffer(residential_buildings[index,], 
                                                  dist = dist_all)$geometry)
    
    pois_eating = pois_around_point[pois_around_point$fclass %in% 
                                      interest_eating,]
    pois_nightlife = pois_around_point[pois_around_point$fclass %in% 
                                         interest_nightlife,]
    pois_culture = pois_around_point[pois_around_point$fclass %in% 
                                       interest_culture,]
    pois_shopping = pois_around_point[pois_around_point$fclass %in% 
                                        interest_shopping,]
    
    pois_around_point_nat = st_intersection(st_buffer(
      residential_buildings[index,], dist = dist_nature), pois_nature)
    
    nearest_dist_nat = st_nearest_points(residential_buildings[index,], 
                                         pois_around_point_nat)
    
    #residential_buildings[index,]$univer_dis = st_length(st_nearest_points(
    #  residential_buildings[index,], pois_uni))
    
    #residential_buildings[index,]$nature_dis = min(st_length(nearest_dist_nat))
    
    pois_around_point_opnv = st_intersection(st_buffer(
      residential_buildings[index,], dist = dist_onpv), pois_opnv)
    
    nearest_dist_opnv = st_nearest_points(residential_buildings[index,], 
                                          pois_around_point_opnv)
    
    pois_noise_road = st_intersection(st_buffer(
      residential_buildings[index,], dist = dist_noise), noise)
    join = st_intersects(residential_buildings[index,], pois_noise_road)
    noise_road = 0
    if (length(join[[1]]) != 0 ) {
      noise_road = max(pois_noise_road[join[[1]], ]$LDEN)
    }
    pois_noise_rail = st_intersection(st_buffer(
      residential_buildings[index,], dist = dist_noise), railway)
    join_2 = st_intersects(residential_buildings[index,], pois_noise_rail)
    noise_rail = 0
    if (length(join_2[[1]]) != 0 ) {
      noise_rail = max(pois_noise_rail[join_2[[1]], ]$DB_Low)
    }
    noise_out = 0
    if (noise_rail > 0 || noise_road > 0){
      noise_out = max(noise_road, noise_rail)}
    
    c(index, nrow(pois_eating), nrow(pois_nightlife), nrow(pois_culture), 
      nrow(pois_shopping),
      st_length(st_nearest_points(residential_buildings[index,], pois_uni)), 
      min(st_length(nearest_dist_nat)), min(st_length(nearest_dist_opnv)), 
      noise_out)
    }

stopImplicitCluster() # switch methods for doparallel 
#stopCluster(cl) #dosnow

end_time = Sys.time()
end_time - start_time
result = as.data.frame(result)
colnames(result) = c('id','eating_dis', 'nightl_dis', 'cultur_dis', 
                     'shoppi_dis', 'univer_dis', 'nature_dis', 
                     'onpv_dis', 'noise_db')

# fix infinite vals in opnv
result$onpv_dis[is.infinite(result$onpv_dis)] = NA


# set up df
residential_buildings$eating_dis = result$eating_dis
residential_buildings$nightl_dis = result$nightl_dis
residential_buildings$cultur_dis = result$cultur_dis
residential_buildings$shoppi_dis = result$shoppi_dis
residential_buildings$univer_dis = result$univer_dis
residential_buildings$nature_dis = result$nature_dis
residential_buildings$onpv_dis   = result$onpv_dis
residential_buildings$noise_db   = result$noise_db

# rescale 
residential_buildings$eating_norm = rescale(result$eating_dis, to = c(0,1))
residential_buildings$nightl_norm = rescale(result$nightl_dis, to = c(0,1))
residential_buildings$cultur_norm = rescale(result$cultur_dis, to = c(0,1))
residential_buildings$shoppi_norm = rescale(result$shoppi_dis, to = c(0,1))
residential_buildings$univer_norm = rescale(result$univer_dis, to = c(0,1))
residential_buildings$nature_norm = rescale(result$nature_dis, to = c(0,1))
residential_buildings$onpv_norm   = rescale(result$onpv_dis, to = c(0,1))
residential_buildings$noise_norm  = - rescale(result$noise_db, to = c(0,1))


#jena= load_n_tansform('findtherightplace_jena.shp')
#fix= load_n_tansform('findtherightplace_jena_fix.shp')

#jena$unvr_ds = fix$unvr_ds
#jena$unvr_nr = fix$unvr_nr

#jena$nois_db = fix$nois_db
#jena$nos_nrm = fix$nos_nrm
# transform back to wgs84 for export
residential_buildings_out = st_transform(jena, 4326)

outgj = residential_buildings_out[c('etng_ds', 'nghtl_d', 'cltr_ds', 'shpp_ds', 'unvr_ds', 
                            'natr_ds', 'onpv_ds', 'nois_db', 'etng_nr', 'nghtl_n',
                            'cltr_nr', 'shpp_nr', 'unvr_nr', 'ntr_nrm', 'onpv_nr',
                            'nos_nrm')]
colnames(outgj)=c('eating_dis', 'nightl_dis', 'cultur_dis', 'shoppi_dis', 
                   'univer_dis', 'nature_dis', 'onpv_dis', 'noise_db', 
                   'eating_norm', 'nightl_norm',
                   'cultur_norm', 'shoppi_norm', 'univer_norm', 'nature_norm', 
                   'onpv_norm',
                   'noise_norm', 'geometry')

# export ----
st_write(residential_buildings_out, paste0("findtherightplace_deliverable2.shp"), 
         layer_options = "ENCODING=UTF-8", delete_dsn = T)
st_write(outgj, paste0("findtherightplace_deliverable2.geojson"), delete_dsn = T)

# view data here?
#mapview(list(residential_buildings[9,], noise[2384, ]), 
#        layer.name = c('buildings', 'noise'))


# this here can be used to clip all osm data to a certain extent ----
#list = list.files("thueringen-latest-free.shp/", 
#                  pattern = "[shp]$" , full.names=T)
#nc <- st_read(list[19])
# for (file in list[1:18]) {
#   file1 = st_read(file)
#   clip = st_intersection(nc, file1)
#   
#   st_write(clip, paste0("clip_", file_path_sans_ext(basename(file)), ".shp"),
#            layer_options = "ENCODING=UTF-8")
# }
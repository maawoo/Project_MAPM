# Mapathon deliverable 1: proxy map
# cut files to umriss
# make buffer around pos and neg features
#
#
library(tools)
library(readtext)
library(sf)
library(tidyverse)
library(mapview)

library(dplyr)

# this here is used to clip all osm data to a certain extent ----
#list = list.files("thueringen-latest-free.shp/", pattern = "[shp]$" , full.names=T)
#nc <- st_read(list[19])
# for (file in list[1:18]) {
#   file1 = st_read(file)
#   clip = st_intersection(nc, file1)
#   
#   st_write(clip, paste0("clip_", file_path_sans_ext(basename(file)), ".shp"), layer_options = "ENCODING=UTF-8")
# }

# simplify loading and not forgetting to transform again
load_n_tansform <- function(filename) {
  return(st_transform(st_read(filename), 25832))
}


list = list.files(pattern = "clip_.+shp$", full.names=T)

build_test_func = load_n_tansform(list[1])

buildings = load_n_tansform(list[1])
#buildings = st_transform(buildings, 25832)
# combine pois
pois_area = load_n_tansform(list[9])
#pois_area = st_transform(pois_area, 25832)
pois_point = load_n_tansform(list[10])
#pois_point = st_transform(pois_point, 25832)
roads = load_n_tansform(list[12])
#roads = st_transform(roads, 25832)
rail = load_n_tansform(list[11])
#rail = st_transform(rail, 25832)
unique(pois_point$fclass)

interest_eating = c('restaurant', 'cafe')
interest_nightlife = c('nightclub', 'pub', 'bar')

pois_eating = rbind(pois_point[pois_point$fclass %in% interest_eating,], st_centroid(pois_area[pois_area$fclass %in% interest_eating,]))
#pois_eating = st_transform(pois_eating, 25832)

# radien für restaurants? 500m 1 km, 1.5 km
#pois_eating_25 = st_buffer(pois_eating, 25)
#pois_eating_25$eating_zone = 1
#pois_eating_75 = st_buffer(pois_eating, 75)
#pois_eating_75$eating_zone = 2
#pois_eating_150 = st_buffer(pois_eating, 150)
#pois_eating_150$eating_zone = 3

#eating_25 = pois_eating_25 %>% 
#  group_by(eating_zone) %>% 
#  summarise()
#eating_75 = pois_eating_75 %>% 
#  group_by(eating_zone) %>% 
#  summarise()
#eating_150 = pois_eating_150 %>% 
#  group_by(eating_zone) %>% 
#  summarise() 

#diff_75_25 = st_difference(eating_75, eating_25)
#diff_150_75 = st_difference(eating_150, eating_75)

#eating = rbind(eating_25, diff_150_75[c(-2)], diff_75_25[c(-2)])


#buildings = st_transform(buildings, 25832)
residential = c('apartments', 'house', 'residential', NA)
residential_buildings = rbind(buildings[buildings$type %in% residential,])
residential_buildings = st_transform(residential_buildings, 25832)
residential_buildings$eating_dis = 0
for (index in 1:nrow(residential_buildings)) {
  #print(index)
  test = st_nearest_points(residential_buildings[index,], pois_eating)
  dists = as.vector(st_length(test))
  #print(test[st_length(test) <= 500,])
  #print(sum(st_length(test))/length(test))
  residential_buildings[index,]$eating_dis = sum(dists < 500)
}


#test = st_nearest_points(residential_buildings, pois_eating)

#residential_buildings$eating_dis = apply(sum(st_length(test))/length(test))


landuse = st_read(list[2])
landuse = st_transform(landuse, 25832)
interest_nature = c('park', 'playground', 'forest', 'meadow', 'grass', 'recreation_ground', 'scrub')

pois_nature = rbind(landuse[landuse$fclass %in% interest_nature,], pois_area[pois_area$fclass %in% interest_nature,])
residential_buildings$nature_dis = 0
for (index in 1:nrow(residential_buildings)) {
  #print(index)
  test = st_nearest_points(residential_buildings[index,], pois_nature)
  #print(sum(st_length(test))/length(test))
  residential_buildings[index,]$nature_dis = min(st_length(test))
}

noise = st_read("noise_db.shp")
noise = st_transform(noise, 25832)

residential_buildings$noise_db = NA
residential_buildings = st_transform(residential_buildings, 25832)

for (index in 1:nrow(residential_buildings)) {
  join = st_intersects(residential_buildings[index,], noise)
  #print(length(join[[1]]))
  if (length(join[[1]]) != 0 ) {
    residential_buildings[index,]$noise_db = mean(noise[join[[1]], ]$LDEN)
  }
}


residential_buildings = st_transform(residential_buildings, 4326)

#normalize = function(x) {
#  return ((x - min(x)) / (max(x) - min(x)))
#}
#residential_buildings$eating_dis=normalize(residential_buildings$eating_dis)
#residential_buildings$nature_dis=normalize(residential_buildings$nature_dis)

st_write(residential_buildings, paste0("res_buildings_wgs84.shp"), layer_options = "ENCODING=UTF-8", delete_dsn = T)

mapview(list(residential_buildings[9,], noise[2384, ]), layer.name = c('buildings', 'noise'))





#mapview(list(eating, out),
        #         layer.name = c("Eating", 'actual point'))
# versuch die nähesten punkte vom roads datensatz im bezug auf restaurants zu finden
# geht, aber es werden linestrings zurückgegeben, die in beim versuch das ergebnis 
# mit c() oder cbind, ect zu verbinden aber in normale listen umgewandelt werden
# u nd deshalb kann man das ergebnis nicht mappen
# roads = st_transform(roads, 25832)
# 
# print(pois_eating[1,])
# out = c(st_linestring())
# for (index in 1:nrow(pois_eating)) {
#   #print(index)
#   proxy = st_intersection(st_buffer(pois_eating[index,], 50), roads)
#   test = st_nearest_points(pois_eating[index,], proxy)
#   test$length = st_length(test)
#   
#   #print(test[test$length == min(test$length)])
#   pos = which(test$length == min(test$length, na.rm = TRUE), arr.ind = TRUE)
#   print(pos)
#   if (length(pos) > 1 ) {
#     pos = pos[1]
#   }
#   print(test[pos])
#   #out = rbind(out, pos)
#   out =  cbind(out, test[pos])
# }
# typeof(out[2])
# st_linestring(out[1])
# test[1]
# 
# mapview(test[1])
# 
# mapview(list(eating, out),
#         layer.name = c("Eating", 'actual point'))

#st_write(eating, paste0("eating.shp"), layer_options = "ENCODING=UTF-8")



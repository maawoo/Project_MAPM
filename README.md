# "Find the Right Place"
A project by Team MAPM for the ["Map your world!"](https://www.agorize.com/en/challenges/heremapathon) mapathon by HERE Maps.

Click here to view our map:
https://maawoo.github.io/Project_MAPM/

## Debugging
Turn off your adblocker and reload the browser window, if any of the layers are not showing up!

## Introduction

Imagine you move to a new city and you have no idea about the different districts
and what they have to offer in terms of culture, nightlife, accessibility to public 
transport, and so on. With this map we want to give people the tool to ***Find the 
Right Place*** for their new home. At least if they want to move to Jena, Germany :wink:  

Several public datasets containing factors for the attractiveness of 
urban spaces were combined to generate one simple to use map indicating
the neighborhood of the highest comfort for each individual.

In the **Layers** tab, single layers can be viewed. The features of each layer 
are selected using reasonable filters in order to portray only those features affected by 
the selected parameter. Generally, the darker the display color, the 
stronger this feature is influenced by the specified parameter. Either negatively
(e.g. in case of noise pollution) or positively (e.g. in case of 
distance to nature). 

In the **Usecases** tab, different use cases have been pre-calculated. They 
all are named for a better designation and contain different parameters.

## Usecases

**Julia** is a young university student with a dog. She needs to be close to 
nature for her dog to feel comfortable. Since she regularly travels, she 
would also like to be close to public transport without having to suffer 
from any noise pollution. Therefore the parameters used are nature and 
public transport as positive and noise pollution as a negative factor.

**Peter** loves to party and enjoy nights out with friends. He studies at
the local university but strongly dislikes getting up early. Noise 
doesn't bother him too much though. The parameters used in the Peter
usecase are density or restaurants and nightlife as well as a close
distance to university. 

**Anna and Klaus** are a middle-aged couple and both work full-time. They 
like arts and theater and often spend the evening with a good meal in
a restaurant and a stage play or concert afterwards. But because 
Anna works for an international company, she needs to be close to 
public transport as well. For Anna and Klaus, the culture and 
restaurants parameter as well as the public transport were combined.

**The Schiller family** consists of Mum and Dad with their two teenage 
daughters. The daughters love shopping in their free time but mum 
and dad rather enjoy some peaceful quietness. Also, the mum has a 
strong pollen allergy and wants to live far from any trees. This 
layer is a combination of shopping as positive and nosie as well 
as nature as negative factors.

## Data

The main source of our data is [OpenStreetMaps](https://www.openstreetmap.org/).
Using R scripts, we extracted building polygons for the city of Jena and did 
calculations for each of these polygons. 
On the one hand, distances to some features were calculated (e.g. distance to 
nature) and on the other hand we counted features (e.g. restaurants) in a certain 
perimeter surrounding the polygon. 

We also included publicly available noise data from the [Thuringia state government](https://www.geoportal-th.de/de-de/Metadaten/Metadatenansicht/uid/80b250a6-4dda-481d-8568-162e20c1cb7a/sid/0) and the [German Railway](https://www.eba.bund.de/DE/Themen/Laerm_an_Schienenwegen/Laermkartierung/laermkartierung_node.html#doc1528304bodyText2).

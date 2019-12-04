# "Find the Right Place"
A project by Team MAPM for the ["Map your world!"](https://www.agorize.com/en/challenges/heremapathon) mapathon by HERE Maps.

Click here to view our map:
https://maawoo.github.io/Project_MAPM/

## Debugging
Turn off your adblocker and reload the browser window, if any of the layers are not showing up!

## Introduction

Imagine you move to a new city and you have no idea about the different districts
and what they have to offer in terms of culture, nightlife, accessibility to public 
transport and so on. With this map we want to give people the tool to ***Find the 
Right Place*** for their new home! (...at least if they want to move to Jena, Germany :wink:)

Several public datasets containing factors for the attractiveness of 
urban spaces were combined to generate one simple to use map indicating
the neighborhood of highest comfort for each individual.

In the **Layers** tab, single layers can be viewed. The features of each layer 
are selected using reasonable filters in order to portray only those features affected by 
the selected parameter. Generally, the darker the display color, the 
stronger this feature is influenced by the specified parameter. Either negatively
(e.g. in case of noise pollution) or positively (e.g. in case of 
proximity to nature). 

In the **Use Cases** tab, different scenarios have been pre-calculated based on different parameters. 
In a later update, we want to give the user more freedom to explore the data and visualize 
his/her own preferences on the map. 

## Use Cases

**Julia** is a young university student, who is going to move to Jena with her dog. 
She loves nature and additionally her dog needs to be close to green spaces to feel comfortable. 
Since Julia regularly travels, she would also like to be close to public transport without 
having to suffer from any noise pollution. 
Parameters used: 
- distance to Nature (+) 
- distance to Public Transport (+)
- Noise Pollution (-)

**Peter** loves to party and enjoys going out with friends. He studies at
the local university but strongly dislikes getting up early. Noise 
doesn't bother him too much though. 
Parameters used: 
- density of Restaurants (+) 
- density of Nightlife (+)
- distance to University (+)

**Anna and Klaus** are a middle-aged couple and both work full-time. They 
like arts and theater and often spend the evening with a good meal in
a restaurant and a stage play or concert afterwards. But because 
Anna works for an international company, she needs to be close to 
public transport as well. 
Parameters used: 
- density of Culture (+) 
- density of Restaurants (+)
- distance to Public Transport (+)

**The Schiller family** consists of Mum and Dad with their two teenage 
daughters. The daughters love shopping in their free time but their parents 
rather enjoy some peaceful quietness. Also, the mum has a 
strong pollen allergy and wants to live far from any trees. 
Parameters used: 
- density of Shops (+)
- Noise Pollution (-)
- distance to Nature (-)

## Pop Up

By clicking on an object in the map, a Pop Up will show up which contains
information about the exact distances and densities for this object.

## Data

The main source of our data is [OpenStreetMaps](https://www.openstreetmap.org/).
Using R scripts, we extracted building polygons for the city of Jena and did 
calculations for each of these polygons. 
On the one hand, distances to some features were calculated (e.g. distance to 
nature) and on the other hand we counted features (e.g. restaurants) in a certain 
perimeter surrounding the polygon. 

We also included publicly available noise data from the [Thuringia state government](https://www.geoportal-th.de/de-de/Metadaten/Metadatenansicht/uid/80b250a6-4dda-481d-8568-162e20c1cb7a/sid/0) and the [German Railway](https://www.eba.bund.de/DE/Themen/Laerm_an_Schienenwegen/Laermkartierung/laermkartierung_node.html#doc1528304bodyText2).
The noise dataset was calculated after EU regulations in a height of 4 m above ground.

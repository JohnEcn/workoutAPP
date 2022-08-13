# **WorkoutApp** - Workout routine manager
WorkoutApp is a web application built with vanilla php. It utilizes a relational database to store the user data, and the communication between the client and the server is done through a REST API.
[API documentation](https://app.swaggerhub.com/apis/JohnEcn/workoutTracker/1#/). 

Check it out [Here](https://workoutbroski.000webhostapp.com/). 
Press **Try a demo** to log in a demo account.

## Features
- Create and manage your workout routines.
- Perform these routines with the guidance of the app.
- Log your performance statistics.
- Track your progress through detailed charts.

## Project structure
#### Backend
The Model consists of six separate modules. Each one is responsible for a totally separate set of data. 
Each module has it's own endpoint that is used by the main API endpoint (index.php) to access each data category.

![](https://i.postimg.cc/zDwPdZHV/Workout-App-Model.png)
#### Frontend
The Controller implements the API endpoint from the client side, transforing each API call to a function.
These functions are used by noumerous other functions to fetch user data.

Each operation in the front-end that requires data from the API, consists of two functions, one that initiates the API call
and the second function (callback) that is called asynchronously when the API call is complete.

###### Log in operation example
![](https://i.postimg.cc/DwQvn4VP/front.png)

#### Database
###### Relational database shcema
![](https://i.postimg.cc/bNbPMWH4/Untitled.png)





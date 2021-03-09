function ajaxRequest(httpPayload,queryParametersStr,calledFrom,callBackMethod)
{
    let domain = window.location.hostname;
    let protocol = "http";
    let apiIndex = protocol + "://" + domain + "/workoutAPP/src/API";
    
    let path;
    let httpMethod;

    switch(calledFrom)
    {   
        //User auth
        case authenticateUser:
            path = apiIndex + "/user/auth";
            httpMethod = "POST";                        
        break;
        case createUser:
            path = apiIndex + "/user/auth";
            httpMethod = "PUT";
        break;
        case endUserAuthentication:
            path = apiIndex + "/user/auth";
            httpMethod = "DELETE";
        break;
        //Workouts Get-Set
        case getWorkoutList:
            path = apiIndex + "/user/workouts";
            httpMethod = "GET";                        
        break;
        case getWorkout:
            path = apiIndex + "/user/workouts";
            httpMethod = "GET";                        
        break;
        case saveWorkout:
            path = apiIndex + "/user/workouts";
            httpMethod = "POST";
        break;
        case renameWorkout:
            path = apiIndex + "/user/workouts";
            httpMethod = "PUT";
        break;
        case deleteWorkout:
            path = apiIndex + "/user/workouts";
            httpMethod = "DELETE";
        break; 
        //Workout exercise delete-Set 
        case insertExercise:
            path = apiIndex + "/user/workouts/exercises";
            httpMethod = "PUT";
        break;
        case deleteExercise:
            path = apiIndex + "/user/workouts/exercises";
            httpMethod = "DELETE";
        break;
        //Session Handle
        case getSession:
            path = apiIndex + "/user/workouts/sessions";
            httpMethod = "GET";
        break;
        case startSession:
            path = apiIndex + "/user/workouts/sessions";
            httpMethod = "POST";
        break;
        case updateSession:
            path = apiIndex + "/user/workouts/sessions";
            httpMethod = "PUT";
        break;
    }    

    if(queryParametersStr != null)
    {
        path = path+queryParametersStr;
    }
    
    var request = new XMLHttpRequest();
    request.open(httpMethod,path);
    request.setRequestHeader("Content-Type", "application/json");
    request.send(httpPayload); 

    request.onreadystatechange = function(){
        if (this.readyState === XMLHttpRequest.DONE) 
        {   
            let statusCode = request.status;
            let responseBody = request.responseText;
 
            callBackMethod(statusCode,responseBody);
        }   
    }
}
//User auth
function authenticateUser(username,password,callBackMethod)
{
    let httpBody = JSON.stringify({'user':username,'pass':password});
    ajaxRequest(httpBody,null,authenticateUser,callBackMethod); 
}
function createUser(username,email,password,passwordConf,callBackMethod)
{
    let httpBody = JSON.stringify({'user':username,'pass':password,'passconf':passwordConf,'email':email});
    ajaxRequest(httpBody,null,createUser,callBackMethod); 
}
function endUserAuthentication(callBackMethod)
{
    let httpBody = "";
    ajaxRequest(httpBody,null,endUserAuthentication,callBackMethod); 
}
//Workouts Get-Set
function getWorkoutList(callBackMethod)
{
    let httpBody = "";
    ajaxRequest(httpBody,null,getWorkoutList,callBackMethod); 
}
function getWorkout(workoutID,callBackMethod)
{
    let httpBody = "";
    let queryParameters = "?wid="+workoutID;
    ajaxRequest(httpBody,queryParameters,getWorkout,callBackMethod); 
}
function saveWorkout(workoutName,exerciseList,callBackMethod)
{
    let httpBody = JSON.stringify({"workout":{'name':workoutName,'exerciseList':exerciseList}});
    ajaxRequest(httpBody,null,saveWorkout,callBackMethod); 
}
function renameWorkout(workoutID,newWorkoutName,callBackMethod)
{
    let httpBody = "";
    let queryParameters = "?wid="+workoutID+"&"+"newName="+newWorkoutName;
    ajaxRequest(httpBody,queryParameters    ,renameWorkout,callBackMethod); 
}
function deleteWorkout(workoutID,callBackMethod)
{
    let httpBody = "";
    let queryParameters = "?wid="+workoutID;
    ajaxRequest(httpBody,queryParameters,deleteWorkout,callBackMethod); 
}
//Workout exercise delete-Set 
function insertExercise(workoutID,exercise,callBackMethod)
{
    let httpBody = JSON.stringify(exercise);;
    let queryParameters = "?wid="+workoutID;
    ajaxRequest(httpBody,queryParameters,insertExercise,callBackMethod); 
}
function deleteExercise(workoutID,exerciseID,callBackMethod)
{
    let httpBody = "";
    let queryParameters = "?wid="+workoutID+"&"+"exid="+exerciseID;;
    ajaxRequest(httpBody,queryParameters,deleteExercise,callBackMethod); 
}
//Session handle 
function getSession(callBackMethod)
{
    let httpBody = "";
    let queryParameters = null;
    ajaxRequest(httpBody,queryParameters,getSession,callBackMethod); 
}
function startSession(workoutID,callBackMethod)
{
    let httpBody = "";
    let queryParameters = "?wid="+workoutID;
    ajaxRequest(httpBody,queryParameters,startSession,callBackMethod); 
}
function updateSession(action,exerciseID,callBackMethod)
{
    let httpBody = "";
    let queryParameters = null;
    if(action != null)
    {
        queryParameters = "?action="+action;
    }
    else if(exerciseID != null)
    {
        queryParameters = "?exid="+exerciseID;
    }
    ajaxRequest(httpBody,queryParameters,updateSession,callBackMethod); 
}
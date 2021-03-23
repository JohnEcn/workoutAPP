function logOut()
{
    endUserAuthentication(logOutResponseHandle);
}
function logOutResponseHandle(httpCode,httpBody)
{
     location.href = "/workoutApp"
}
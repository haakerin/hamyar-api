# hamyar - API

the hamyar panel is a website that you can select your language to learn it and hamyar give you a plan to practice or learn in the week and this repository is APIs of hamyar website(backend).

you can use [this link](https://github.com/haakerin/Hamyar) to go [frontend repository](https://github.com/haakerin/Hamyar) of hamyar.

## signup API

in this API first validate the username, name, email, password for security then check that do not exist username in database then hash the password and insert in database
<br>
<br>
<br>

## login API

in this API first validate the (email or username) and password for security then check the user exist in database then verifying the hash password and if verifying sucssefull generate a token to response for client
<br>
<br>
<br>

## add-plan API

in this API first decrypt the token and username get from token then check the limit time have been observed then get user id and subject id then computing the plan level and analysis the plan every week then insert plan into database
<br>
<br>
<br>

## get-plans API

in this API we have two way first get sub plan of each plan and second is get all plan to show in list
first the token verifying and then get the plans and response the plans
<br>
<br>
<br>

## delete-plans API

in this API first verifying the token and then delete the plan

<br>
<br>
<br>

## get-info API

in this API first token verifying and then response the user-info

<br>
<br>
<br>

## islogin API
this API get the token and check for if the token is valid or invalid

<br>
<br>
<br>

## update-users API
this API is update user information if the values are valid and do not exists in database

<br>
<br>
<br>

## functions and config file
the functions file is my functions that use in project and config file is the  database  configurations

<br>
<br>
<br>

## Demo

you can see the Demo All of [hamyar](https://hamyar.iran.liara.run) project here.

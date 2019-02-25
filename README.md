# curl
TASK for Supermetrics

DOCS
============

1. curl.php input:

POST: curl.php

PARAMS:

client_id : ju16a6m81mhid5ue1z3v2g0uh</br>
email : your@email.address</br>
name : Your Name

RETURNS

JSON:
- month_stat : month number : integer number of past months (0-6)</br>
              - max : Longest post by character length / month</br>
              - average : Average character length / post / month</br>
- week_stat  : week number : integer number of past weeks (0-x)</br>
              - Total posts split by week(posts / week)</br>
- month_user : month number : integer number of past months (0-6)</br>
              - User_id number</br>
              - Average number of posts per user / month</br>


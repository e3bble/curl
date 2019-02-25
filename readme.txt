DOCS
============

1. curl.php input:

POST: curl.php

PARAMS:

client_id : ju16a6m81mhid5ue1z3v2g0uh
email : your@email.address
name : Your Name

RETURNS

JSON:
- month_stat : month number : integer number of past months (0-6)
              max     - Longest post by character length / month
              average - Average character length / post / month
- week_stat  : week number : integer number of past weeks (0-x)
              - Total posts split by week(posts / week)
- month_user : month number : integer number of past months (0-6)
              - User_id number
              - Average number of posts per user / month

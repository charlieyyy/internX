# internX
> Wenzheng Hu <whu14@illinois.edu>    Zeyu Wu <zeyuwu2@illinois.edu>
> Mingli Yang <myang46@illinois.edu>  Xilun Jin<xjin12@illinois.edu>


## Table of contents
* [General info](#general-info)
* [Data and Technologies](#data-and-technologies)
* [Code Examples](#code-examples)
* [Features](#features)
* [License](#license)
* [Contact](#contact)

## General info
We made a job searching website only for college students to help them with job hunting. Many internship websites right now are not that useful. We are going to get all the resources of top companies’ websites and from school’s job searching platform, and then post to our website. We do not invite recruiters. What we do is to set up a big database of internship positions for students to search. Simple and clean. Besides that, there are other functionalities that can increase the efficiency of job hunting and save times, such as job recommendation and resume parsing to autofill the profile.



## Data and Technologies
As a website which dedicates to provide internships for college students, our database mainly contains information of users and jobs. These are 2 major entities in our project. And the user has many attributes, such as name, school, major and so on. Jobs also have many attributes such as location, industry and title. Additionally, in our design of filtering search, we choose four of them to perform the basic search function. Moreover, all these entities may have subsets. For example, for a job listing, main attribute would be company and location. It also has requirements for the job, leading to the fact that requirements have so many attributes that it could also be subset of job in the scope of ER design. Specific design of ER diagram has been provided below. 

Our choices of platform/languages:
1.	Client-Side Scripting: Javascript
2.	Server-Side Scripting: PHP
3.	Web Server: Apache
4.	Database: MySQL


## Code Examples
Update a user’s attributes
UPDATE `user` SET `$attributesName[$x]`='$attributes[$x]' WHERE `uid`='$uid

Check if experience exist in database
SELECT EXISTS(SELECT 1 FROM `Experience` WHERE `uid`='$uid' and `uidExpNum`='$x')


## Features
* Basic Functions: 
1.	Position Lists:
We manually input position from big companies and lists the real-time open positions (outdated positions will be deleted from the database).
2.	Sign-up/ Sign-in:
User will be recommended to register first so that the webpage’s advanced function can better work for the user. There will be two ways for the user to fill in the registration form: he can either type in the information manually; or upload his resume and check the autofill from his parsed resume. (The webpage will provide parse and autofill function.) The user is welcome to re-upload a new resume, edit or delete his information later.
3.	Favorite Positions:
User can bookmark/unbookmark his interested/favorite positions. He can check these positions as a list in his home page. He can rearrange the order of the bookmarked positions in his personal position list.

* Advanced Functions:
1.	Resume Parsing:
The webpage will parse the uploaded resume and autofill the registration form for the user so that the user doesn’t have to type in the registration form (usually long) by himself.
2.	Job Recommendation:
The website will recommend position to users according to the jobs they bookmarked. 


## License

Distributed under the MIT License.

## Contact

[Mingli Yang](https://www.linkedin.com/in/myang46)

Project Link: [https://github.com/charlieyyy/internX/](https://github.com/charlieyyy/internX/)

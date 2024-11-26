# Online Bank - L3 Databases-Networks

**Creation of an online banking network as part of our Databases and Networks courses at CY Tech.**  
The aim is to offer the possibility of opening two types of account: a current account and savings accounts. It is possible to make payments and transfers between accounts belonging to the same user and between accounts belonging to different users. Users can view their latest transactions and the amounts in each of their accounts, as well as open new accounts and close existing ones.    

The network client simulates the operation of a payment terminal. The web browser lets you consult the status of your accounts, displaying the last transactions carried out, the total money available to the user, and the breakdown between accounts.

## Technical specifications

This project has the following architecture : 
- A database server using the PostgreSQL RDBMS,
- A web server with a PHP connection to the database server,
- A network server written in Java with a connection to the database server,
- A network client written in Java and a web client (browser).

===

- Languages : Java, SQL, HTML, CSS, PHP (use of PHP Data Objects to access the Database)
- RDBMS : PostgreSQL
- Database manager : pgAdmin4
- CSS toolkit : Bootstrap

## Installation process
1. Place the folder ClientWeb in the folder of your web server
2. Install the postgres DBMS on your machine if necessary
3. Install the database using the instructions in the commentary of the SQL script file
4. Open client and server folders with a java IDE such as Eclipse
5. You can use the website and the network client !

## Hosted on

...

## Contributors

Kévin BERNARD, Nicolas CIBULKA

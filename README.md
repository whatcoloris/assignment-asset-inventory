# assignment-asset-inventory

An example Asset Inventory database using PHP, MySQL, and JSON.

## Old System

A client has been keeping track of its assets in an old card catalog.

They are looking to convert our system to store the content in a database.

A typical card looks like this:

'''
{
Signed out to:  Sonny Sohnnson
Location:       4-15C
Phone:          518-123-4567
Device ID:      CL123543
Category:       Computer
Description:    2020 iFruit Vaporware
Purchased:      January 3, 2020
}
'''

## DDL for the Table

Provide the DDL for the table(s) you would use to store this information. The SQL used can be either MySQL or MSSQL flavored.

Date should be a date field.
The category should be enumerated: computer | peripheral | audio | video | other.

For this exercise, we won’t be building the database, just planning out the table(s).

## Submission Form

Build a web form for entering the data into the database.

The form should be styled simply and demonstrate an understanding of responsive design and accessibility best practices.
The form submission will be sent to the server via JSON.

Since you are not building the database, instead please aggregate the data on the server using PHP and JSON. To show the data set is there, display the data using var_dump() at the bottom of the page.

The data from the form will need to persist on the server between calls. This should be either done with PHP Session variables or using JSON in a hidden form field.
The page should include:

The form.
- A list of the last five entries.
- The var_dump() from the server.
- (Bonus points) Show a warning message if the user enters a duplicate device ID. The warning should display the existing record and ask the user if they want to overwrite it. This can be generated server-side or client-side.

This exercise should be done in a single file. Make no assumptions about available resources except for PHP and a modern browser.

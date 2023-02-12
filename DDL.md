# DDL for Asset Inventory

Rather than use a separate Table for each Column, we'll put all records within one table marked "Assets" - we could also use different Tables for each "Category" but since it is requested that these be enumerated, this approach does not seem encouraged.

## Designing the Table

Given our example card:

```
Signed out to:  Sonny Sohnnson
Location:       4-15C
Phone:          518-123-4567
Device ID:      CL123543
Category:       Computer
Description:    2020 iFruit Vaporware
Purchased:      January 3, 2020
```

We can see (at least) 7 Columns will be necessary.

1) The first Column, "Signed out to" will be a String indicating the name of the one who has signed the asset out.
2) The second Column, "Location" will be a String, since it consists of numbers, letters, and the special character '-'
3) The third Column, "Phone" will be a String, since in addition to the numeric information it must also account for the '-' part of the phone numbers.
4) The fourth Column, "Device ID" will be a String, and will also be the Primary Key.
5) The fifth Column, "Category" will be Numeric (TINYINT), with each of the possible categories (computer | peripheral | audio | video | other) assigned a persistent number representing each.
6) The sixth Column, "Description" will be a String.
7) The seventh Column, "Purchased" will be a Date format.
8) In addition to the Card Catalog information given, since we will need to be able to retrieve the 5 most recent entries in the Database, we will include a final Column "Added_TS" with the TIMESTAMP data type, which will keep track of when an entry was added.

## Pertinent DDL Commands

### Create the Database and Table

```
CREATE DATABASE AssetInventory;
USE AssetInventory;
CREATE TABLE Assets (Signed_Out_To VARCHAR(255), Location VARCHAR(255), Phone VARCHAR(255), Device_ID VARCHAR(255), Category TINYINT, Description VARCHAR(255), Purchased DATE, Added_TS TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (Device_ID));
```

The end of the line creating the table defines "Device_ID" as the Primary Key for the database, satisfying our "bonus points" requirement that the "Device_ID" for each card be unique, at least from the . MySQL will return an error if we attempt to provide a "Device_ID" which is already in the database.

I've left the other fields able to be NULL since there was not information given as to whether each assets requires all fields to be filled.

### Adding to the Database

```
INSERT INTO Assets (Signed_Out_To, Location, Phone, Device_ID, Category, Description, Purchased) VALUES ('Research Foundation', 'Albany, NY', '269-317-5393', 'BM123456', 4, 'An assignment', '2023-02-07');
```

Here is an example INSERT command which adds a single Asset into the database. This will automatically check to see if the "Device_ID" field is unique and will throw an error if not. Note that it will also automatically create a timestamp.

### Retrieving the last 5 entries

```
SELECT * FROM Assets ORDER BY Added_TS DESC LIMIT 5;
```
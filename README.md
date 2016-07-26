Calendar51 Documentation
========================

## General
--------------
Calendar51 project was build using **Symfony 2.8** framework and a 3-layered application design.
I wanted to try a different approach so it might not be a very good implementation.

There are 3 layers:
** application    - contains controller (no views since there are only API endpoints), the only layer coupled to the framework
** domain         - contains models and services
** infrastructure - contains repositories and mappers

* Layers are decoupled using interfaces.
* No ORM or annotations were used.
* Models are immutable, anemic and decoupled.
* A mapper is used to get an object instance.
* Objects are added in the response using the symfony serializer.
* Request data is validated using "respect/validation" component.
* Exception based validation.
* Each validation throws specific exception that is mapped to the response.
* Defensive programming approach in order to make the code behave in a predictable manner.

## Installation
--------------
* Run ```composer install```
* Update ```parameters.yaml``` database parameters with your credentials.
* Run ```php app/console calendar51:schema:update tables.sql``` to update your database schema (it also contains 1 dummy data).

## API Documentation:
--------------

* Create event:
    - route: **/event/add**
    - method: **POST**
    - parameters:
        - description ```string``` (e.g. 'Retrospective Meeting')
        - date_format ```string``` (e.g. 'd-m-Y H:i:s')
        - from_date   ```string``` (e.g. '28-07-16 10:30:00')
        - to_date     ```string``` (e.g. '28-07-16 11:30:00')
        - location    ```string``` (e.g. 'Room 302')
        - comment     ```string``` (**optional**/ e.g. 'Please be there in time')
    - response:
        - 200 status code:
```
   {
     "data": {
       "id": 12
     }
   }
```
        - 400 status code:
```
   {
     "message": "To Date param must be greater that From Date param"
   }
```
        - 500 status code:
```
   {
     "message": "There was a problem adding this event."
   }
```

* Update event:
    - route: **/event/update**
    - method: **POST**
    - parameters:
        ```id``` is the only mandatory field. The request may contain the updated fields.
        - id          ```int```    (e.g. 1)
        - description ```string``` (**optional**/ e.g. 'Retrospective Meeting2')
        - date_format ```string``` (**optional**/ e.g. 'd-m-Y H:i:s')
        - from_date   ```string``` (**optional**/ e.g. '28-07-16 12:30:00')
        - to_date     ```string``` (**optional**/ e.g. '28-07-16 13:30:00')
        - location    ```string``` (**optional**/ e.g. 'Room 305')
        - comment     ```string``` (**optional**/ e.g. 'Better eat something before coming.')
    - response:
        - 200 status code:
```
   {
     "data": "Event `1` successfully updated."
   }
```
        - 400 status code:
```
   {
     "message": "Please provide an event id."
   }
```
        - 500 status code:
```
   {
     "message": "Unable to update the requested event."
   }
```

* Delete event:
    - route: **/event/delete**
    - method: **POST**
    - parameters:
        - id          ```int```    (e.g. 1)
    - response:
        - 200 status code:
         ```
            {
              "data": "Event `1` successfully deleted."
            }
         ```
        - 400 status code:
         ```
            {
              "message": "Please provide an event id."
            }
         ```
        - 500 status code:
         ```
            {
              "message": "Unable to delete the requested event."
            }
         ```


* Get event:
    - route: **/event/get/{id}**
    - method: **GET**
    - parameters:
        - id          ```int```    (e.g. 1)
    - response:
        - 200 status code:
         ```
            {
              "data": {
                "id": "1",
                "description": "Daily Standup",
                "dateFormat": "d-m-y H:i:s",
                "fromDate": "28-07-16 10:30:00",
                "toDate": "28-07-16 11:30:00",
                "location": "305 Room",
                "comment": null
              }
            }
         ```
        - 400 status code:
         ```
            {
              "message": "Please provide an event id."
            }
         ```
        - 500 status code:
         ```
            {
              "message": "PHP Exception"
            }
         ```

* Get events chronologically:
    - route: **/event/all**
    - method: **GET**
    - parameters: none
    - response:
        - 200 status code:
         ```
            {
              "data": [
                {
                  "id": "3",
                  "description": "Important Retro Meeting",
                  "dateFormat": "d-m-y H:i:s",
                  "fromDate": "27-07-16 10:30:00",
                  "toDate": "27-07-16 11:30:00",
                  "location": "301 Room",
                  "comment": "The whole team should assist."
                },
                {
                  "id": "2",
                  "description": "Daily Standup",
                  "dateFormat": "d-m-y H:i:s",
                  "fromDate": "28-07-16 10:30:00",
                  "toDate": "28-07-16 11:30:00",
                  "location": "200 Room",
                  "comment": "Please bring cookies."
                }
              ]
            }
         ```
        - 500 status code:
         ```
            {
              "message": "PHP Exception"
            }
         ```
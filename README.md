##**Using Simple CRUD for Really Simple Todo**
 
 #### Headers must be set (list below) before sending requests:
    'accept', 'application/json'
    'Content-Type', 'application/json'

 ##### Body to send
    using json:
    
    {
        "title": "String",
        "content": "String",
        "inStatus": integer
    }
    
    body require when creating and updating item
 
 ##### GET method
    not using
 
 ##### POST method
     works only on
     - http://.../api
     - http://.../api/create
     - http://.../api/view/{id}
     
 ##### PUT method
    works only on
    - http://.../api/update/{id}
    
 ##### DELETE method
    works only on
    - http://.../api/delte/{id}
 
 ##### Return result
    on /create/view:
    {
        "id": integer,
            "item": {
                "id": Integer,
                "title": "String",
                "content": "String",
                "createdAt": "String<DateTime>",
                "inStatus": Integer
            },
            "status": Integer
    }
    
    on update/delete:
    {
        "result": boolean,
        "status": integer
    }
    
 ##### Extra
    added PostMan collection in project 4 testing
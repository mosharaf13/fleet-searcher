### Future plan

1. To accomodate more users and more keywords we can dispatch multiple workers which 
will run asynchronously and store the scrapped data in db.
2. If we decide to do this then the frontend will immediately get a response after successfully submitting the file. 3
3. We can use periodic poling to get updates from server. that way we get to see partial results.
5. We will have to design a system to let frontend know that all the keywords have been scrapped.
6. We can make use of redis for this. before dispatching any job we will put a status for this on redis.
7. Then when frontend polling request is coming in we can just check if all the uploaded keywords data have been saved to db and returned to user or not.
8. If we need a more realtime experience, we can make use of websockets. we will run a websocket server on a port and push data whenever available

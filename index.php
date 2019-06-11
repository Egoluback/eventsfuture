<!DOCTYPE HTML>
<html>
  <head>
    <title>Events in future</title>
    <script src = "vue.js"></script>
    <link href = "https://fonts.googleapis.com/css?family=Oswald" rel = "stylesheet">
    <link href = "css/main.css" rel = "stylesheet">
  </head>
  <body>
    <div id = "app">
      <div id = "mainText"><input type = "number" v-model.number = "mainText" @input = "change(true)" @change = "restart()"></div>
      <div id = "info">
      Интервал: +1 в {{ timeInterval }} миллисекунд<br />
      Интервал уменьшается в два раза каждые <input id = "info-num-interval" type = "number" v-model.number = "border" @input = "change(false)"> секунд <br />
      Итераций пройдено: {{ numIter }}<br />
        Секунд прошло: {{ numSec }}
        <div id = "events-container">
            <div class = "event" v-for = "event in nowEvents">{{ event.value }}</div>
        </div>
        <div id = "link-container">
            <a href = "addForm.php">Добавить событие</a>
        </div>
      </div>
      <div id = "chron-events">
        <div id = "chron-events-title">Хронология событий</div>
        <div class = "event" v-for = "event in arrEvents">{{ event.year }}: {{ event.value }}</div>
      </div>
    </div>
    <?php
        require('config.php');
        $query = "SELECT * FROM events ORDER BY year";
        $result = mysqli_query($mysqli, $query);
        $data = array();
        while($event = $result -> fetch_assoc()){
            array_push($data, $event);
        }

    ?>
    <script>
        const events = [];
        <?php 
            foreach ($data as $i){
                ?> events.push({"year": <?php echo $i['year']?>, "value": "<?php echo $i['value']?>"}); <?php
            }
        ?>

        const app = new Vue({
            el: "#app",
            data: {
                mainText: 2019,
                timeInterval: 1000,
                numIter: 0,
                border: 5,
                numSec: 0,
                isGoing: false,
                infoYear: "",
                nowEvents: [],
                mainTimeout: "",
                arrEvents: []
            },
            created: function() {
                setTimeout(this.init, 1000);
                setInterval(() => {
                    this.numSec ++;
                },1000);
            },
            methods: {
                init: function(){
                    app.mainText ++;
                    app.numIter ++;
                    if (events.length > 0){
                        for (let i = 0; i < events.length; i++){
                            if (app.mainText >= events[i].year){
                                if (i > 0){
                                    if (events[i + 1]){
                                        if (events[i - 1].year != events[i].year && events[i + 1].year != events[i].year || events[i - 1].year != events[i].year && events[i + 1].year == events[i].year)
                                            app.nowEvents = [];
                                    } else{
                                        if (events[i - 1].year != events[i].year) app.nowEvents = [];
                                    }
                                } else app.nowEvents = [];
                                app.nowEvents.push({'year': events[i].year, 'value': events[i].value});
                                if (app.arrEvents.indexOf({'year': events[i].year, 'value': events[i].value}) == -1 && app.mainText == events[i].year)
                                    app.arrEvents.unshift({'year': events[i].year, 'value': events[i].value});
                            }
                        }
                    }
                    if (app.numSec % app.border == 0 && app.numSec != 0 && app.isGoing == false){
                        app.timeInterval = app.timeInterval / 2;
                        app.isGoing = true;
                        setTimeout(() => {
                            app.isGoing = false;
                        }, app.border * 1000);
                    }
                    app.mainTimeout = setTimeout(app.init, app.timeInterval);
                },
                change: function(isMainText){
                    this.isGoing = false;
                    this.timeInterval = 1000;
                    this.numIter = 0;
                    this.numSec = 0;
                    this.nowEvents = [];
                    this.arrEvents = [];
                    if (isMainText)
                        clearTimeout(this.mainTimeout);
                },
                restart: function(){
                    this.numSec = 0;
                    setTimeout(this.init, 1000);
                }
            }
        });
    </script>
  </body>
</html>
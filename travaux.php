<html>
<head>
<title>En Travaux</title>


<script>

var time
var output

function Init(){
	time = 1;
	StartTimer()
}

function convertOutput(){
	var hours = 0
	var mins = 0
	var secs = time
	
	while(secs >= 60){
		secs -= 60;
		mins += 1;
	}
	
	if(secs < 10){
		secs = "0" + secs;
	}
	
	while(mins >= 60){
		mins -= 60;
		hours += 1;
	}
	
	if(mins < 10){
		mins = "0" + mins;
	}
	
	output = hours + ":" + mins + ":" + secs;
}

function StartTimer(){
	time += 1;
	convertOutput();
	document.getElementById("time").innerHTML = output;
	self.setTimeout("StartTimer()", 1000);
}

</script>

</head>
<body onLoad="javascript:Init()" bgcolor="black">
    <div style="text-align:center; color:white; font-size:14pt; font-weight:bold">Pendant les travaux nous vous offrons cet interlude musical.<br />ejoingnez nous sur irc sur <a href="http://www.cgichat.epiknet.org/">#cybercity</a></div>
<object width="100%" height="100%"><param name="movie" value="loituma.swf">
<embed src="loituma.swf" width="100%" height="100%" /></object>

<div style="text-align:center; color:white; font-size:14pt; font-weight:bold">



Vous tournez depuis <span id="time">00:00:00</span>.
</div>
</body>


</html>

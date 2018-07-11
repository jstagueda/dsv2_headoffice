  var timer = 0;
  function set_interval()
  {
  //the interval 'timer' is set as soon as the page loads<br />
  timer = setInterval("auto_logout()",50000);
  // the figure '10000' above indicates how many milliseconds the timer be set to.<br />
  //Eg: to set it to 5 mins, calculate 5min= 5x60=300 sec = 300,000 millisec. So set it to 300000<br />
  }

  function reset_interval()
  {
  //resets the timer. The timer is reset on each of the below events:<br />
  // 1. mousemove   2. mouseclick   3. key press 4. scroliing<br />
  //first step: clear the existing timer<br />

  if (timer != 0) {
  clearInterval(timer);
  timer = 0;
  //second step: implement the timer again<br />
  timer = setInterval("auto_logout()",2700000);
  // completed the reset of the timer<br />
  }
  }

  function auto_logout()
  {	
  //this function will redirect the user to the logout script<br />
  window.location="sessionexpire.php";
  } 
  
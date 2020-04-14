(function () {

  var divisors = { second: 1000 };
  divisors.minute = divisors.second * 60;
  divisors.hour = divisors.minute * 60;
  divisors.day = divisors.hour * 24;

  // Handler when the DOM is fully loaded
  document.onreadystatechange = function () {
    updateTimers();

    if (document.readyState === 'interactive' || document.readyState === 'complete') {
      var countdownTimer = setInterval(updateTimers, 1000);
    }
  }

  var updateTimers = function() {
    var timerSlots = document.querySelectorAll('.js-countdown');

    timerSlots.forEach(function (timer) {

      var countDownDate = new Date(timer.dataset.countdownDate);
      var now = new Date().getTime();
      var distance = countDownDate - now;

      var days = Math.floor(distance / divisors.day);
      var hours = Math.floor((distance % divisors.day) / divisors.hour);
      var minutes = Math.floor((distance % divisors.hour) / divisors.minute);
      var seconds = Math.floor((distance % divisors.minute) / divisors.second);
      var millis = distance % divisors.second;

      var timerText = "";
      switch (timer.dataset.countdownGranularity) {
        case "ms":
          timerText = millis + " ms " + timerText;
        case "s":
          timerText = seconds + " seconds " + timerText;
        case "m":
          timerText = minutes + " minutes " + timerText;
        case "h":
          timerText = hours + " hours " + timerText;
        case "d":
          timerText = days + " days " + timerText;
          break;
      }

      timer.textContent = timerText.trim();
    });
  }

})();

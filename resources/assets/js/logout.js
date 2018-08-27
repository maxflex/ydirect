var logout_interval;

logout_interval = false;

window.logoutCountdownClose = function() {
  clearInterval(logout_interval);
  logout_interval = false;
  return $('#logout-modal').modal('hide');
};

window.logoutCountdown = function() {
  var seconds;
  seconds = 60;
  $('#logout-seconds').html(seconds);
  $('#logout-modal').modal('show');
  return logout_interval = setInterval(function() {
    seconds--;
    $('#logout-seconds').html(seconds);
    if (seconds <= 1) {
      clearInterval(logout_interval);
      return setTimeout(function() {
        return location.reload();
      }, 1000);
    }
  }, 1000);
};

window.continueSession = function() {
  $.get("/auth/continue-session");
  return logoutCountdownClose();
};

window.listenToSession = function(app_key, user_id) {
  var channel, pusher;
  pusher = new Pusher(app_key, {
    cluster: 'eu'
  });
  channel = pusher.subscribe('session.' + user_id);
  return channel.bind("App\\Events\\LogoutSignal", function(data) {
    switch (data.action) {
      case 'notify':
        return logoutCountdown();
      case 'destroy':
        return redirect('/logout');
    }
  });
};

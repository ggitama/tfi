<footer class="footer">
    <!-- <div><a href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>https://coreui.io">CoreUI </a><a href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>https://coreui.io">Bootstrap Admin Template</a> © 2022 creativeLabs.</div>
    <div class="ms-auto">Powered by&nbsp;<a href="<?= htmlentities(base_url('Assets/Dashboard/dist/')) ?>https://coreui.io/docs/">CoreUI UI Components</a></div> -->
</footer>
</div>

<!-- CoreUI and necessary plugins-->
<!-- <script src="vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
<script src="vendors/simplebar/js/simplebar.min.js"></script> -->


<!-- Plugins and scripts required by this view-->
<!-- <script src="vendors/chart.js/js/chart.min.js"></script> -->
<!-- <script src="vendors/@coreui/chartjs/js/coreui-chartjs.js"></script> -->
<!-- <script src="vendors/@coreui/utils/js/coreui-utils.js"></script> -->
<!-- <script src="js/main.js"></script> -->

<!-- 
<script>
    var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
    triggerTabList.forEach(function(triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)

        triggerEl.addEventListener('click', function(event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
    var triggerEl = document.querySelector('#myTab a[href="#profile"]')
    bootstrap.Tab.getInstance(triggerEl).show() // Select tab by name

    var triggerFirstTabEl = document.querySelector('#myTab li:first-child a')
    bootstrap.Tab.getInstance(triggerFirstTabEl).show() // Select first tab
</script>
<script>
window.onload = function() {
  let frameElement = document.getElementById("rizki");
  let doc = frameElement.contentDocument;
  doc.body.innerHTML = doc.body.innerHTML + '<style>.h2 {color:red;}</style>';
}
</script> -->

<script>
    var val = {}
    val.<?= $this->security->get_csrf_token_name(); ?> = "<?= $this->security->get_csrf_hash(); ?>"


    // setInterval(() => {
    //     $.ajax({
    //         url: '<?= htmlentities(base_url('Dashboard/Dashboard_c/csrf_token')) ?>',
    //         type: "post",
    //         data: val,
    //         success: function(res) {
    //             val.<?= $this->security->get_csrf_token_name(); ?> = res
    //         },
    //         error: function(jqXHR, textStatus, errorThrown) {
    //             // alert('gagal');
    //             // if(!alert('Failed Token! Refresh Page!')){
    //                 // window.location.reload();
    //             // }
    //         }
    //     });
    // }, (1000));
   


    // setInterval(() => {
    //     $(".flex .align-center").firstChild.remove()
        
    // }, (4000));
    // console.log(csrf_hash);

    function valid_username(username) {
        validates = true
        msgs = 'Please match the required the username field ! </br>'
        // var pola= new RegExp(/^[a-z A-Z]+$/);
        var pola = new RegExp(/^[a-zA-Z0-9_-]+$/);
        if (pola.test(username)) {
            msgs = ''
            validates = false
        }
        return {
            'validates': validates
        }
    }
    
    function valid_password(password){
        validates = true
        msgs = 'Please match the required the username field ! </br>'
        // var pola= new RegExp(/^[a-z A-Z]+$/);
        // var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/);
        // var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/);
        var pola = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*();:<>?.,{}|_+=])[A-Za-z\d!@#$%^&*();:<>?.,{}|_+=]{8,}$/);
        if (pola.test(password)) {
            msgs = ''
            validates = false
        }
        return {
            'validates': validates
        }

    }


    function valid_iframe_name(iframe) {
        validates = true
        msgs = 'Please match the required the iframe field ! </br>'
        // var pola= new RegExp(/^[a-z A-Z]+$/);
        var pola = new RegExp(/^[a-z A-Z0-9_-]+$/);
        if (pola.test(iframe)) {
            msgs = ''
            validates = false
        }
        return {
            'validates': validates
        }
    }

    function valid_iframe_tag(iframe) {
        validates = true
        msgs = 'Please match the required the iframe field ! </br>'
        // var pola= new RegExp(/^[a-z A-Z]+$/);
        console.log(iframe);
        var pola = new RegExp(/^[a-z A-Z0-9_#=%-]+$/gm);
        if (pola.test(iframe)) {
            msgs = ''
            validates = false
        }
        return {
            'validates': validates
        }
    }

    function valid_nama(nama) {
        msgs = 'Please match the required the nama field ! </br>'
        validates = true
        var pola = new RegExp(/^[0-9a-zA-Z\s'-]{1,100}$/);
        if (pola.test(nama)) {
            validates = false
            msgs = ''
        }
        console.log(pola.test(nama), 'tesssa', validates);
        // console.log(object);
        return {
            'validates': validates
        }
    }
</script>




<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Session Expiration Warning</h4>
            </div>
            <div class="modal-body">
                <p>
                    You've been inactive for a while. For your security, we'll log you
                    out automatically. Click "Stay Online" to continue your session.
                </p>
                <p>
                    Your session will expire in
                    <span class="bold" id="sessionSecondsRemaining">120</span>
                    seconds.
                </p>
            </div>
            <div class="modal-footer">
                <button id="extendSession" type="button" class="btn btn-default btn-success" data-dismiss="modal">
                    Stay Online
                </button>
                <button id="logoutSession" type="button" class="btn btn-default" data-dismiss="modal">
                    Logout
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mdlLoggedOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">You have been logged out</h4>
            </div>
            <div class="modal-body">
                <p>Your session has expired.</p>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= base_url('Assets/idle-timer.js') ?>"></script>
<script src="<?= base_url('Assets/respond.js') ?>"></script>
<script src="<?= base_url('Assets/moment.js') ?>"></script>


<script>
    (function($) {
        var
            session = {
                //Logout Settings
                inactiveTimeout: 1800000, //(ms) The time until we display a warning message
                warningTimeout: 10000, //(ms) The time until we log them out
                minWarning: 10000, //(ms) If they come back to page (on mobile), The minumum amount, before we just log them out
                warningStart: null, //Date time the warning was started
                warningTimer: null, //Timer running every second to countdown to logout
                logout: function() { //Logout function once warningTimeout has expired
                    //window.location = settings.autologout.logouturl;
                    // $("#mdlLoggedOut").modal("show");
                    $.ajax({
                    url: '<?= htmlentities(base_url('Login_c/logout/')) ?>',
                    type: "post",
                    data: val,
                    beforeSend: function() {
                        $("#loader").show();
                    },
                    complete: function() {
                        $("#loader").hide();
                    },
                    success: function(res) {
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });
                },

                //Keepalive Settings
                keepaliveTimer: null,
                keepaliveUrl: "",
                keepaliveInterval: 10000, //(ms) the interval to call said url
                keepAlive: function() {
                    $.ajax({
                        url: session.keepaliveUrl
                    });
                }
            };


        $(document).on("idle.idleTimer", function(event, elem, obj) {
            //Get time when user was last active
            var
                diff = (+new Date()) - obj.lastActive - obj.timeout,
                warning = (+new Date()) - diff;

            //On mobile js is paused, so see if this was triggered while we were sleeping
            if (diff >= session.warningTimeout || warning <= session.minWarning) {
                // $("#mdlLoggedOut").modal("show");
                $.ajax({
                    url: '<?= htmlentities(base_url('Login_c/logout/')) ?>',
                    type: "post",
                    data: val,
                    beforeSend: function() {
                        $("#loader").show();
                    },
                    complete: function() {
                        $("#loader").hide();
                    },
                    success: function(res) {
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('gagal');
                    }
                });
            } else {
                //Show dialog, and note the time
                $('#sessionSecondsRemaining').html(Math.round((session.warningTimeout - diff) / 1000));
                $("#myModal").modal("show");
                session.warningStart = (+new Date()) - diff;

                //Update counter downer every second
                session.warningTimer = setInterval(function() {
                    var remaining = Math.round((session.warningTimeout / 1000) - (((+new Date()) - session.warningStart) / 1000));
                    if (remaining >= 0) {
                        $('#sessionSecondsRemaining').html(remaining);
                    } else {
                        session.logout();
                    }
                }, 1000)
            }
        });

        // create a timer to keep server session alive, independent of idle timer
        session.keepaliveTimer = setInterval(function() {
            session.keepAlive();
        }, session.keepaliveInterval);

        //User clicked ok to extend session
        $("#extendSession").click(function() {
            clearTimeout(session.warningTimer);
        });
        //User clicked logout
        $("#logoutSession").click(function() {
            session.logout();
        });

        //Set up the timer, if inactive for 10 seconds log them out
        $(document).idleTimer(session.inactiveTimeout);
    })(jQuery);
</script>
</body>

</html>
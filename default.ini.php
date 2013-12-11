; <?php exit(); __halt_compiler(); ?>
; Config file for DoNotDisturb app

[general]
default_timer = '30'    ; in minutes
polling_time = '10'     ; in seconds

[site_text]
busy_text = "Please do not interrupt me for this much longer. Thanks!"
free_text = "I'm not busy, please feel free to interrupt."

[site_buttons]
start_text = "Start"
stop_text  = "Stop"
reset_text = "Reset"

[paths]
time_file = 'time_ending.txt'
install_url = 'http://jerlance.com/dnd'


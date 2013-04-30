#!/bin/sh
if [ -z "$1" ]; then
	echo 'usage: source mk_templates.sh modulesnumber'
else

cp -a blocks/apcal_coming_schedule.html blocks/apcal$1_coming_schedule.html
cp -a blocks/apcal_new_event.html blocks/apcal$1_new_event.html
cp -a blocks/apcal_todays_schedule.html blocks/apcal$1_todays_schedule.html
cp -a blocks/apcal_minical_ex.html blocks/apcal$1_minical_ex.html
cp -a apcal_event_detail.html apcal$1_event_detail.html
cp -a apcal_event_list.html apcal$1_event_list.html
perl -pe "s/db\\:apcal_/db\\:apcal$1_/g" <apcal_print.html >apcal$1_print.html

fi

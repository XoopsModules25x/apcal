#!/bin/sh
if [ -z "$1" ]; then
	echo 'usage: source mk_templates.sh modulesnumber'
else

cp -a blocks/apcal_coming_schedule.tpl blocks/apcal$1_coming_schedule.tpl
cp -a blocks/apcal_new_event.tpl blocks/apcal$1_new_event.tpl
cp -a blocks/apcal_todays_schedule.tpl blocks/apcal$1_todays_schedule.tpl
cp -a blocks/apcal_minical_ex.tpl blocks/apcal$1_minical_ex.tpl
cp -a apcal_event_detail.tpl apcal$1_event_detail.tpl
cp -a apcal_event_list.tpl apcal$1_event_list.tpl
perl -pe "s/db\\:apcal_/db\\:apcal$1_/g" <apcal_print.tpl >apcal$1_print.tpl

fi

#!/bin/bash

curl "http://localhost/?fungsi=set_file_presentasi&jml_arg=1&id_device=1&arg1=null"
curl "http://localhost/?fungsi=set_file_presentasi&jml_arg=1&id_device=2&arg1=null"
curl "http://localhost/?fungsi=set_file_presentasi&jml_arg=1&id_device=3&arg1=null"

curl "http://localhost/?fungsi=set_action&jml_arg=1&id_device=1&arg1=null"
curl "http://localhost/?fungsi=set_action&jml_arg=1&id_device=2&arg1=null"
curl "http://localhost/?fungsi=set_action&jml_arg=1&id_device=3&arg1=null"

curl "http://localhost/?fungsi=set_sound_volume&jml_arg=1&id_device=1&arg1=100"
curl "http://localhost/?fungsi=set_sound_volume&jml_arg=1&id_device=2&arg1=100"
curl "http://localhost/?fungsi=set_sound_volume&jml_arg=1&id_device=3&arg1=100"

curl "http://localhost/?fungsi=set_mute&jml_arg=1&id_device=1&arg1=false"
curl "http://localhost/?fungsi=set_mute&jml_arg=1&id_device=2&arg1=false"
curl "http://localhost/?fungsi=set_mute&jml_arg=1&id_device=3&arg1=false"

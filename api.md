# SET
## 1. set_file_presentasi
>berfungsi untuk menset file yang akan ditampilkan  
>**jumlah argumen:** 1  
>**arg1:** url tempat file dapat didownload (public url)

**contoh request**
```
http://localhost/index.php?fungsi=set_file_presentasi&jml_arg=1&id_device=1&arg1=http://www.axmag.com/download/pdfurl-guide.pdf
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    // url tempat user dapat mendownload file presentasi (string)
    "nama_file": "http://www.axmag.com/download/pdfurl-guide.pdf"
  }
}
```

## 2. set_action
>berfungsi untuk mengontrol presentasi  
>**jumlah argumen:** 1  
>**arg1:** action presentasi [play/pause/stop/next/prev]

**contoh request**
```
http://localhost/index.php?fungsi=set_action&jml_arg=1&id_device=1&arg1=play
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    // url tempat user dapat mendownload file presentasi (string)
    "nama_file": "http://www.axmag.com/download/pdfurl-guide.pdf",
    // action yang sedang dijalankan presentasi (string)
    "action": "play"
  }
}
```

## 3. set_sound_volume
>berfungsi untuk mengontrol besar volume presentasi  
>**jumlah argumen:** 1  
>**arg1:** besar volume (0-100)

**contoh request**
```
http://localhost/index.php?fungsi=set_sound_volume&jml_arg=1&id_device=1&arg1=76
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    // besar volume presentasi sekarang (0-100) (int)
    "volume_now": 76,
    // besar volume presentasi sebelumnya (0-100) (int)
    "volume_before": 0
  }
}
```

## 4. set_mute
>berfungsi untuk mengontrol mute/tidak volume presentasi  
>**jumlah argumen:** 1  
>**arg1:** status mute [true/false]

**contoh request**
```
http://localhost/index.php?fungsi=set_mute&jml_arg=1&id_device=1&arg1=true
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    // status mute presentasi sekarang (boolean)
    "status_now": true,
    // status mute presentasi sebelumnya (boolean)
    "status_before": false
  }
}
```
---
# GET
## 1. get_file_presentasi
>berfungsi untuk mendapatkan informasi file presentasi sekarang  

**contoh request**
```
http://localhost/index.php?fungsi=get_file_presentasi&jml_arg=0&id_device=1
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    //url tempat user dapat mendownload file presentasi (string)
    "nama_file": "http://www.axmag.com/download/pdfurl-guide.pdf"
  }
}
```

## 2. get_action
>berfungsi untuk mendapatkan informasi action presentasi sekarang  

**contoh request**
```
http://localhost/index.php?fungsi=get_action&jml_arg=0&id_device=1
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    //url tempat user dapat mendownload file presentasi (string)
    "nama_file": "http://www.axmag.com/download/pdfurl-guide.pdf",
    // action yang sedang dijalankan presentasi (string)
    "action": "play"
  }
}
```

## 3. get_sound_status
>berfungsi untuk mendapatkan informasi volume presentasi sekarang  

**contoh request**
```
http://localhost/index.php?fungsi=get_sound_status&jml_arg=0&id_device=1
```

**contoh balasan**
```javascript
{
  "status": true, // berhasil/tidak request dieksekusi (boolean)
  "data": {
    // besar volume presentasi (int)
    "volume": 76,
    // status mute presentasi (boolean)
    "mute": true
  }
}
```
---
##untuk setiap request yang gagal, akan mendapat balasan dengan format sebagai berikut
```javascript
{
  "status": false,
  "data": {
    // pesan error
    "message": // alasan error (string)
  }
}
```

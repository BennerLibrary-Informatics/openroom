<!--style for the library footer popup-->
<style>
.modal-content {
    position: relative;
    margin: auto;
    padding: 0;
    border: 5px solid #888;
    width: 35%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
    -webkit-animation-name: animatetop;
    -webkit-animation-duration: 0.4s;
    animation-name: animatetop;
    animation-duration: 0.4s
}

.modal-header {
    padding: 2px 16px;
    background-color: white;
    color: black;
}

.modal-body {
  padding: 2px 16px;
  word-wrap: break-word;
}

.modal-footer {
    padding: 2px 10px;
    background-color: white;
    color: black;
}

.libraryfooter {
  background-color: #54406b;
  font-size: 15px;
  color: #FFFFFF;
  border: 0px;
  padding: 5px;
  position: relative;
}

libraryfooter-ul {
   list-style: none;
   margin: 0px;
   padding: 0px;
}

a.nav-link { /* Adjust 'a' color in style.css to make all links white*/
  color: white;
  text-decoration: none;
  display: inline;
  margin-left: -10px;
  margin-right: -10px;
  line-height: 12px;
  z-index: 10;
  position: relative;
}

a.nav-link:link
{
  color: #FFFFFF;
  text-decoration: none;
}

a.nav-link:hover
{
  color: #FFFFFF;
  text-decoration: underline;
}

#left {
  float: left;
}

#right {
  float: right;
  text-align: right;
}

#center {
  text-align:center;
  position: absolute;
  top: 20px;
  right: 0;
  bottom: 0;
  left: 0;
}
</style>
</div>
<div class = "libraryfooter">
  <div id = "left">
  <libraryfooter-ul>
    <br>
    <li>
      Copyright (C) 2012 Ball State University Libraries
    </li>
    <li>
      Modified by Olivet Nazarene University
    </li>
  </libraryfooter-ul>
</div>

<div id = "right">
  <libraryfooter-ul>
    <li>
      <a href="https://library.olivet.edu/index.php" class = "nav-link" target="_blank">Benner Library & Resource Center</a>
    </li>
    <li>
      <a href="https://www.olivet.edu/" class = "nav-link" target="_blank">Olivet Nazarene University</a>
    </li>
    <li>
      One University Ave · Bourbonnais, IL 60914</br>
      Phone: 815-939-5354 · Fax: 815-939-5170
    </li>
  </libraryfooter-ul>
</div>
<div id="clear_both"></div>
<div id = "center">
  <libraryfooter-ul>
    <img src = 'https://library.olivet.edu/img/logo.svg'>
  </libraryfooter-ul>
</div>
</div>

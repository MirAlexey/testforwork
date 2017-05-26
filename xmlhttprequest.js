function getXmlHttpRequest(){
  if(window.XMLHttpRequest){
    return new XMLHttpRequest();
  }
  try{
    return new ActiveXObject("Msxml2.XMLHTTP");    
  }catch(e){}
  try{
    return new ActiveXObject("Microsoft.XMLHTTP");  
  }catch(e){}
}
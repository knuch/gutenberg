const svgIcons = () => {
  const ajax = new XMLHttpRequest();
  const svgPath = window.svgPath || data.svgPath || 'icons/icons.svg';
  ajax.open('GET', svgPath, true);
  ajax.send();
  ajax.onload = function (e) {
    if (e.target.status == 200) {
      var div = document.createElement('div');
      div.innerHTML = ajax.responseText;
      document.body.insertBefore(div, document.body.childNodes[0]);
    }
  };
};

export default svgIcons;

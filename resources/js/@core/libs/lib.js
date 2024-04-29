//import * as moment from 'moment'

export const Functions = {
  replaceChar: function(param) {
    if(param==null) return
    const acentos = {'á':'a','é':'e','í':'i','ó':'o','ú':'u','ñ':'n','Á':'A','É':'E','Í':'I','Ó':'O','Ú':'U','Ñ':'N'};
    return param.replace(/[áéíóúñÁÉÍÓÚÑ]/g, m => acentos[m]);
  },
  downloadFileCsvEncode(arrData, nameFile){
    var charset = document.characterSet || document.charset;
    let csvContent = "data:text/csv;charset="+charset+",";
    csvContent += [
        Object.keys(arrData[0]).join(";"),
        ...arrData.map(item => Object.values(item).join(";"))
    ]
        .join("\n")
        .replace(/(^\[)|(\]$)/gm, "");   

    const data = encodeURI(csvContent);

    this.exportReport(data, nameFile);

  },
  downloadFileCsv(arrData, nameFile){
    var charset = document.characterSet || document.charset;
    let csvContent = "data:text/csv;charset="+charset+",";
    csvContent += [
        Object.keys(arrData[0]).join(";"),
        ...arrData.map(item => Object.values(item).join(";"))
    ]
        .join("\n")
        .replace(/(^\[)|(\]$)/gm, "");   

    const data = csvContent;

    this.exportReport(data, nameFile);

  },
  formatDate(created_at) {
      return moment(created_at).format('DD/MM/YYYY');
  },
  exportReport(href, name){
    const link = document.createElement('a');
    link.href = href;
    link.setAttribute('download',  name);
    link.setAttribute('target',  "_blank");
    link.click();
    link.remove();
  },
  forceFileDownload(response, filename){
        const url = window.URL.createObjectURL(new Blob([response.data]))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', filename) //or any other extension
        //link.setAttribute('target',  "_blank");
        document.body.appendChild(link)
        link.click()
        link.remove()
  },
};
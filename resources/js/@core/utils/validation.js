import { isEmpty } from './index'

export const required = value => (value && value.length ? true : 'Este campo es requerido')

export const requiredObject = value => !!value || 'Este campo es requerido'

export const emailValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  // eslint-disable-next-line
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

  if (Array.isArray(value)) {
    return value.every(val => re.test(String(val)))
  }

  return re.test(String(value)) || 'Este campo debe contener un email válido'
}

export const passwordValidator = password => {
  /* eslint-disable no-useless-escape */
  if(password==null || password=='') return 'El campo debe contener mínimo un carácter especial, una mayúscula, una minúscula y un número, con un mínimo de 8 caracteres'
  if(!/[A-Z]/.test(password)) return 'El campo debe contener mínimo una mayúscula'
  if(!/[a-z]/.test(password)) return 'El campo debe contener mínimo una minúscula'
  if(!/[0-9]/.test(password)) return 'El campo debe contener mínimo un número'
  if(!/[!@#$%&*()+]/.test(password)) return 'El campo debe contener mínimo un carácter especial !@#$%&*()+'
  if(password.length < 8) return 'El campo debe contener mínimo 8 caracteres'

  return true

  /*const regExp = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%&*()+]).{8,}/
  const validPassword = regExp.test(password)

  return (
    // eslint-disable-next-line operator-linebreak
    validPassword ||
    'El campo debe contener mínimo un carácter especial, una mayúscula, una minúscula y un número, con un mínimo de 8 caracteres'
  )*/
}

export const confirmedValidator = (value, target) =>
  // eslint-disable-next-line implicit-arrow-linebreak
  value === target || 'El campo de confirmación no coincide'

export const between = (value, min, max) => () => {
  const valueAsNumber = Number(value)

  return (Number(min) <= valueAsNumber && Number(max) >= valueAsNumber) || `Enter number between ${min} and ${max}`
}

export const integerValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  if (Array.isArray(value)) {
    return value.every(val => /^-?[0-9]+$/.test(String(val)))
  }

  return /^-?[0-9]+$/.test(String(value)) || 'This field must be an integer'
}

export const regexValidator = (value, regex) => {
  if (isEmpty(value)) {
    return true
  }

  let regeX = regex
  if (typeof regeX === 'string') {
    regeX = new RegExp(regeX)
  }

  if (Array.isArray(value)) {
    return value.every(val => regexValidator(val, { regeX }))
  }

  return regeX.test(String(value)) || 'The Regex field format is invalid'
}

export const alphaValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  // const valueAsString = String(value)

  return /^[A-Z]*$/i.test(String(value)) || 'The Alpha field may only contain alphabetic characters'
}

export const urlValidator = value => {
  if (value === undefined || value === null || value.length === 0) {
    return true
  }
  /* eslint-disable no-useless-escape */
  const re = /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/

  return re.test(value) || 'URL es inválido'
}

export const lengthValidator = (value, length) => {
  if (isEmpty(value)) {
    return true
  }

  return value.length == length || `Este campo debe contener ${length} caracteres`
}

export const minlengthValidator = (value, length) => {
  if (isEmpty(value)) {
    return true
  }

  return value.length >= length || `Este campo debe contener mínimo ${length} caracteres`
}

export const maxlengthValidator = (value, length) => {
  if (isEmpty(value)) {
    return true
  }
  
  return value.length <= length || `Este campo debe contener máximo ${length} caracteres`
}

export const alphaDashValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  const valueAsString = String(value)

  return /^[0-9A-Z_-]*$/i.test(valueAsString) || 'Algunos carácteres no son permitidos'
}

export const alphaDasNameValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  const valueAsString = String(value)

  return /^[0-9A-ZÀ-ÿ\u00f1\u00d1_-\s]*$/i.test(valueAsString) || 'Algunos carácteres no son permitidos'
}

export const numberValidator = value => {
  if (isEmpty(value)) {
    return true
  }

  const valueAsString = String(value)

  return /^[0-9,]*$/i.test(valueAsString) || 'Solo números o decimales'
}

export const minNumber = (value, min) => () => {
  const valueAsNumber = Number(value)

  return (Number(min) <= valueAsNumber ) || `Debe ingresar un valor mínimo`
} 

export const sch_required = (checks) =>{

  /* if (((checks[0] == 1) || (checks[1] == 2)) && ((checks[2] == 3) || (checks[3] == 4))) 
  {
    return true
  }

  return 'Elegir al menos una opción'*/

  if ((checks.includes('1') || checks.includes('2') ) || (checks.includes('3') || checks.includes('4') ) ) {
    return true
  }
  return 'Elegir al menos una opción'
}

export const fileValidator = value => {
  console.log(value)
  if(!value.length) return false
  var ext = value[0].name.substring(value[0].name.lastIndexOf(".")+1);
  if(ext != 'jpeg' && ext != 'png' && ext != 'pdf' && ext != 'docx') return 'Tipo de archivo no válido'
  if(value[0].size > 2000000 ) return 'No puede exceder los 2 MB!';
  return true
  //return !value || !value.length || value[0].size < 2000000 || 'No puede exceder los 2 MB!'
  
}

//export const fechaHasta = value =>  {
  export const fechaHasta = (value, hasta = null) =>  {
    if(hasta == null){
      var fecha = new Date();
      var hasta = fecha.toISOString().slice(0, 10); // Formatear como 'YYYY-MM-DD'  
    }
    console.log(hasta)
    if(value == '' || value == null) return true
    return value <= hasta || 'La fecha ingresada no puede ser mayor que la fecha actual ni la fecha "Hasta"';
  } 
  
  export const fechaDesde = (value, desde = null) =>  {
    if(value == '' || value == null) return true
    if(desde == '' || desde == null) return true
    
    return value >= desde || 'La fecha ingresada no puede ser menor a la fecha "desde"';
  } 
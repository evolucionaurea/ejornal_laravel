window.loading = obj => {
	let show = obj != undefined && 'show' in obj ? obj.show : true;
	///loader_queue = show ? loader_queue+1 : loader_queue-1;
	let message = obj != undefined && 'message' in obj ? obj.message : 'Trabajando...';
	if(show){
		$('#loading').addClass('active').find('.text').html(message);
	}else{
		$('#loading').removeClass('active');
		if(obj.callback){obj.callback();}
	}
}
window.get_form = f => {
	var fd = $(f).serializeArray();
	var d = {};
	d.required = [];
	$(fd).each((k,v)=>{
		if (d[v.name] !== undefined){
			if (!Array.isArray(d[v.name])) {
				d[v.name] = [d[v.name]];
			}
			d[v.name].push(v.value);
		}else{
			d[v.name] = v.value;
			if($(f).find(`[name="${v.name}"]`).prop('required')){
				d.required.push(v.name);
			}
		}
	});
	return d;
}
window.in_array = (value,array=[])=>{
	if(array.length==0) return false;
	let found = false;
	let key = 0;
	$.each(array,(k,v)=>{
		if(value==v){
			found = true;
			key = k;
		}
	});

	if(!found) return false;
	return true;
}
window.dom = (el,classes='',id='') => {
	let output = $(`<${el}>`).clone();
	if(id!='') {
		output.attr('id',id);
	}
	if(classes!=''){
		output.addClass(classes);
	}
	return output;
}
window.random = (min, max) => {
	return Math.floor(Math.random() * (max - min)) + min;
}
window.get_template = template => {
	return new Promise((resolve,reject)=>{
		axios.get(template)
			.then(response=>{
				resolve(response.data);
			})
			.catch(error=>{
				reject(error);
			});
	});
}
String.prototype.capitalize = function(){
  const lower = this.toLowerCase();
  return this.charAt(0).toUpperCase() + lower.slice(1);
}
window.get_week_day = day=>{
	const days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
	return days[day]
}
window.get_formatted_date = date=>{
	const day = String(date.getDate()).padStart(2,'0')
	const month = String(date.getMonth() + 1).padStart(2,'0')
	const year = date.getFullYear()
	return `${day}/${month}/${year}`
}
window.get_hours_minutes = date=>{
	const hours = String(date.getHours()).padStart(2,'0')
	const minutes = String(date.getMinutes()).padStart(2,'0')
	return `${hours}:${minutes}`
}
window.get_full_formatted_date = date=>{
	const date_formatted = window.get_formatted_date(date)
	const time = window.get_hours_minutes(date)

	return `${date_formatted} ${time}`
}
import axios from 'axios'

export function UploadImage(data){
	console.log(data)
	var config = {
		  headers:{'Content-Type':'multipart/form-data'}
	  };
	return axios.post(process.env.VUE_APP_BASE_API+'/upload/image',data,config);
}
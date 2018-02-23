var theForm = document.forms["form"];

function getHR() {
	var theForm = document.forms["hbr"];
	var heart_rate = theForm.elements["HB"];
	var HR = 0;
	if(heart_rate.value != "") {
		HR = parseInt(heart_rate.value);
	}
	return HR;
}

function getGender() {
	var gender = "M";
	var theForm = document.forms["form"];
	var selectedGender = theForm.elements["gender"];
	for (var i = 0; i < selectedGender.length; i++) {
		if(selectedGender[i].checked) {
			gender = selectedGender[i].value;
			break;
		}
	}
	if(gender == "male") gender = "M";
	if(gender == "female") gender = "F";
	return gender;
}

function getTrainingTime() {
	var theForm = document.forms["form"];
	var TrainingTime = theForm.elements["training_time"];
	var howmany = 0;
	if(TrainingTime.value != "") {
		howmany = parseInt(TrainingTime.value);
	}
	howmany = howmany / 60;
	return howmany;
}

function getage() {
	var theForm = document.forms["form"];
	var age = theForm.elements["age"];
	var howmany = 0;
	if(age.value != "") {
		howmany = parseInt(age.value);
	}
	return howmany;
}

function getWeight() {
	var theForm = document.forms["form"];
	var weight = theForm.elements["weight"];
	var howmany = 0;
	if(weight.value != "") {
		howmany = parseInt(weight.value);
	}
	return howmany;
}

function calculateTotal() {
	var gender = getGender();
	if(gender == "M") {
		var caloriesBurned = ((-55.0969 + (0.6309 * getHR()) + (0.1988 * getWeight()) + (0.2017 * getage())) / 4.184) * 60 * 2 * getTrainingTime();
		var power = caloriesBurned * (1000/3600);
		var watts_kg = power / getWeight();
		caloriesBurned = caloriesBurned * getTrainingTime();
	}
	else if(gender == "F") {
		var caloriesBurned = ((-20.4022 + (0.4472 * getHR()) + (0.1263 * getWeight()) + (0.074 * getage())) / 4.184) * 60 * 2 * getTrainingTime();
		var power = caloriesBurned * (1000/3600);
		var watts_kg = power / getWeight();
		caloriesBurned = caloriesBurned * getTrainingTime();
	}
	

	//display results
	document.getElementById('calories').setAttribute("value", Math.round(caloriesBurned * 100) / 100);
	document.getElementById('power').setAttribute("value", Math.round(power * 100) / 100);
	document.getElementById('watts').setAttribute("value", Math.round(watts_kg * 100) / 100);
}
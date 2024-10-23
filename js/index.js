const sign_in_btn = document.querySelector("#sign-in-btn");
const sign_up_btn = document.querySelector("#sign-up-btn");
const container = document.querySelector(".container");
sign_up_btn.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_btn.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});
function showpass() {
	var x = document.getElementById("myInput");
	if (x.type === "password") {
	  x.type = "text";
	} else {
	  x.type = "password";
	}
}

function forgotpass(){		
	var name="";
	var email="";
	var id="2";
	Swal.fire({
		title: "Forgot Password",
		text: "Enter your name:",
		input: 'text',                
		showCancelButton: true        
	}).then((result) => {
		if (result.value){			
			  name=result.value;
			  delete result;
			  Swal.fire({
				title: "Forgot Password",
				text: "Enter your email:",
				input: 'email',                
				showCancelButton: true        
			}).then((result) => {
				if (result.value){			
					  email=result.value;
					  delete result;
						Swal.fire({
							title: "Your Enter:",
							text: "Name: " + name +". Email: "+email+".",
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',		               
							showCancelButton: true, 
							confirmButtonText: 'Procced',
							reverseButtons: true
						}).then((result) => {
							if (result.value){			
								document.getElementById("name").value=name;
								document.getElementById("email").value=email;
								document.getElementById("id").value=id;
								document.getElementById("signupform").submit();                                                                                                         
							}
						});					                                                                                                              
				}
			});                                                                                                            
		}
	});	
}


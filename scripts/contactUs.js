function validateForm() {
			var isValid = true;
			var form = document.getElementById('contactForm');
			
			//Clear previous error messages
			document.querySelectorAll('#contactForm .error').forEach(function(div) {
				div.textContent = '';
			});
			
			// Validate salutation
			if (form['sal'].value.trim() === '') {
				document.getElementById('salutationError').textContent = 'Please select your salutation.';
				isValid = false;
			}
			
			// Validate Name
			if (form['name'].value.trim() === '') {
				document.getElementById('nameError').textContent = 'Name is required.';
				isValid = false;
			}
			
			//Validate email
			let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
			
			if (form['email'].value.trim() === '') {
				document.getElementById('emailError').textContent = 'Email is required.';
				isValid = false;
			} else if (!emailPattern.test(form['email'].value)) {
				document.getElementById('emailError').textContent = 'Email is not valid.';
				
				isValid = false;
			}
			
			// Validate Phone Number
			if (form['phone'].value.trim() === '') {
				document.getElementById('phoneError').textContent = 'Phone number is required.';
				
				isValid = false;
			} else if (!/^\d{10,15}$/.test(form['phone'].value)) {
				document.getElementById('phoneError').textContent = 'Enter a valid phone number (10-15 digits).';
				
				isValid = false;
			}
			
			//Validate Enquiry Type
			if (![...form['enquiry[]']].some(checkbox => checkbox.checked)) {
				document.getElementById('enquiryError').textContent = 'Please select at least one type of enquiry.';
				
				isValid = false;
			}
			
			//Validate subject
			if (form['subject'].value.trim() === '') {
				document.getElementById('subjectError').textContent = 'Message is required.';
				
				isValid = false;
			}
			
			if (isValid) {
				form.submit();
			}
		}
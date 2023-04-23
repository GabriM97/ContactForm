<script setup>
    import axios from 'axios';

    const comparePassword = async function (e) {
        setTimeout(function () {
            const password = 'test';
            const confirmPassword = e.target.value;
            if (password === confirmPassword) {
                // CLEAR INLINE ERROR MESSAGE
                return;
            }

            // TODO: change this to inline msg
            console.log('Passwords don\'t match: ' + confirmPassword);
        }, 1000);
    }

    const handleRegister = function () {
        // disable form and buttons
        
        // show loading 

        axios.post(
            '/api/user/register', {
                name: this.name,
                email: this.email,
                password: this.password
            }
        ).then((res) => {
            console.log(res); return;
            localStorage.setAccessToken(res.data.plainTextToken, res.data.expires_at);
        }).catch((err) => {
            // print inline error to user (?)
            console.log(err);
        })
    };

    const emit = defineEmits(['loggedInEvent']);

    emit('loggedInEvent', true)

</script>

<template>
    <div class="mt-10">

        <div id="register_form_container" class="flex flex-col p-5">

            <h1 class="text-3xl">Register</h1>

            <form action="#" id="register_form" @submit.prevent="handleRegister">
                <!-- Name -->
                <div class="flex flex-col my-3" id="name_container">
                    <label for="register_form_name" class="font-medium">Name</label>
                    <input type="text" name="name" id="register_form_name" class="border-gray-300 border-2 rounded-xl" required>
                </div>
                
                <!-- Email -->
                <div class="flex flex-col my-3" id="email_container">
                    <label for="register_form_email" class="font-medium">Email</label>
                    <input type="email" name="email" id="register_form_email" class="border-gray-300 border-2 rounded-xl" required>
                </div>

                <!-- Password -->
                <div class="flex flex-col my-3" id="password_container">
                    <label for="register_form_password" class="font-medium">Password</label>
                    <input type="password" name="password" id="register_form_password" minlength="8" class="border-gray-300 border-2 rounded-xl" required>
                </div>

                <!-- Confirm Password -->
                <div class="flex flex-col my-3" id="confirm_password_container">
                    <label for="register_form_confirm_password" class="font-medium">Confirm Password</label>
                    <input type="password" name="confirm_password" id="register_form_confirm_password" minlength="3" 
                           class="border-gray-300 border-2 rounded-xl" @input="comparePassword" required
                    >
                </div>

                <!-- Button -->
                <div class="flex flex-col my-6 justify-center items-center" id="submit_container">
                    <input type="submit" name="send" id="register_form_submit" value="Register" class="border-gray-300 border-2 rounded-md py-2 w-1/3 cursor-pointer">
                    <p class="mt-5">Already registered? <span class="underline cursor-pointer" @click="">Login now!</span></p>
                </div>
            </form>
        </div>
    </div>
</template>
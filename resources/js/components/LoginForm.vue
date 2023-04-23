<script setup>
    import axios from 'axios';
    import { reactive } from 'vue';

    const emit = defineEmits(['loggedInEvent', 'showRegisterFormEvent', 'errorEvent'])
    
    const login = reactive({
        email: '',
        password: ''
    });

    const handleLogin = function () {
        axios.post(
            '/api/user/login', {
                email: login.email,
                password: login.password,
                validateStatus: status => status < 400
            }
        ).then ((res) => {
            emit('loggedInEvent', {
                token: res.data.data.plainTextToken, 
                expiryDate: res.data.data.accessToken.expires_at
            })
        }).catch (err => {
            emit('errorEvent', err.message);
        })
    };
</script>

<template>
    <div class="mt-10">

        <div id="login_form_container" class="flex flex-col p-5">

            <h1 class="text-3xl">Login</h1>

            <form action="#" id="login_form" @submit.prevent="handleLogin">
                <!-- Email -->
                <div class="flex flex-col my-3" id="email_container">
                    <label for="login_form_email" class="font-medium">Email</label>
                    <input type="email" v-model="login.email" id="login_form_email" class="border-gray-300 border-2 rounded-xl" required>
                </div>

                <!-- Password -->
                <div class="flex flex-col my-3" id="password_container">
                    <label for="login_form_password" class="font-medium">Password</label>
                    <input type="password" v-model="login.password" id="login_form_password" minlength="8" class="border-gray-300 border-2 rounded-xl" required>
                </div>

                <!-- Button -->
                <div class="flex flex-col my-6 justify-center items-center" id="submit_container">
                    <input type="submit" name="send" id="login_form_submit" value="Login" class="border-gray-300 border-2 rounded-md py-2 w-1/3 cursor-pointer">
                    <p class="mt-5">Not registered yet? <span class="underline cursor-pointer" @click="emit('showRegisterFormEvent', true)">Register now!</span></p>
                </div>
            </form>
        </div>
    </div>
</template>
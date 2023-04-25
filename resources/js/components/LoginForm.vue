<script setup>
    import axios from 'axios';
    import { ref, reactive, onMounted } from 'vue';
    import Spinner from './Spinner.vue';

    const emit = defineEmits(['loggedInEvent', 'showRegisterFormEvent', 'errorEvent'])
    
    onMounted(() => {
        // clear error messages when mounted
        emit('errorEvent', '');
    });

    const login = reactive({
        email: '',
        password: ''
    });

    const showLoadingSpinner = ref(false);
    const opacityClass = ref('');
    
    const handleLogin = function () {
        // show spinner and disable form
        toggleSpinner();

        axios.post(
            '/api/user/login', {
                email: login.email,
                password: login.password
            }
        ).then ((res) => {
            emit('loggedInEvent', {
                token: res.data.data.plainTextToken, 
                expiryDate: res.data.data.accessToken.expires_at
            })
        }).catch (err => {
            if (err.response != undefined) {
                emit('errorEvent', err.response.data.error_message);
                return;
            }
            emit('errorEvent', err.message);
        }).finally(() => {
            toggleSpinner();
        });
    };

    const toggleSpinner = function () {
        showLoadingSpinner.value = !showLoadingSpinner.value;
        opacityClass.value = opacityClass.value === '' ? 'opacity-50' : '';
    }
</script>

<template>
    <div id="login_form_container" class="p-5 relative" :class="opacityClass">

        <Spinner v-if="showLoadingSpinner" />

        <h1 class="text-3xl font-bold mb-5">Login</h1>

        <form action="#" id="login_form" class="flex flex-col gap-y-4" @submit.prevent="handleLogin" :disabled="showLoadingSpinner">
            <!-- Email -->
            <div class="flex flex-col" id="email_container">
                <label for="login_form_email" class="font-medium">Email</label>
                <input type="email" v-model="login.email" id="login_form_email" class="border-gray-300 border-2 rounded-xl" 
                        :disabled="showLoadingSpinner" required>
            </div>

            <!-- Password -->
            <div class="flex flex-col" id="password_container">
                <label for="login_form_password" class="font-medium">Password</label>
                <input type="password" v-model="login.password" id="login_form_password" minlength="8" class="border-gray-300 border-2 rounded-xl" 
                        :disabled="showLoadingSpinner" required>
            </div>

            <!-- Button -->
            <div class="flex flex-col my-3 justify-center items-center" id="submit_container">
                <input type="submit" name="send" id="login_form_submit" value="Login" :disabled="showLoadingSpinner"
                        class="border-gray-300 border-2 rounded-md py-2 w-1/3 cursor-pointer">
                <p class="mt-5">Not registered yet? <span class="underline cursor-pointer py-2" @click="!showLoadingSpinner && emit('showRegisterFormEvent', true)">Register now!</span></p>
            </div>
        </form>
    </div>
</template>
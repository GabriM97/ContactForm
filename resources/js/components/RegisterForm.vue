<script setup>
    import axios from 'axios';
    import { ref, reactive, onMounted } from 'vue';
    import Spinner from './Spinner.vue';

    const emit = defineEmits(['registeredEvent', 'showLoginFormEvent', 'errorEvent']);

    onMounted(() => {
        // clear error messages when mounted
        emit('errorEvent', '');
    });

    const register = reactive({
        name: '',
        email: '',
        password: '',
        confirmPassword: ''
    });

    const showLoadingSpinner = ref(false);
    const opacityClass = ref('');
    const disableRegister = ref(false);

    const comparePasswords = async function () {
        if (register.confirmPassword === '') {
            disableRegister.value = false;
            return;
        }

        disableRegister.value = true;

        setTimeout(function () {
            if (register.password === register.confirmPassword) {
                disableRegister.value = false;
                emit('errorEvent', '');
                return;
            }
            emit('errorEvent', 'Passwords don\'t match');
        }, 2000);
    }

    const handleRegister = function () {
        // show spinner and disable form
        toggleSpinner();

        axios.post(
            '/api/user/register', {
                name: register.name,
                email: register.email,
                password: register.password
            }
        ).then((res) => {
            emit('registeredEvent', {
                token: res.data.data.plainTextToken, 
                expiryDate: res.data.data.accessToken.expires_at
            })
        }).catch((err) => {
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
    <div class="mt-10">
        
        <div id="register_form_container" class="flex flex-col p-5 relative" :class="opacityClass">
            
            <Spinner v-if="showLoadingSpinner"/>

            <h1 class="text-3xl">Register</h1>

            <form action="#" id="register_form" @submit.prevent="handleRegister" :disabled="showLoadingSpinner">
                <!-- Name -->
                <div class="flex flex-col my-3" id="name_container">
                    <label for="register_form_name" class="font-medium">Name</label>
                    <input type="text" v-model="register.name" id="register_form_name" class="border-gray-300 border-2 rounded-xl" 
                           :disabled="showLoadingSpinner" required>
                </div>
                
                <!-- Email -->
                <div class="flex flex-col my-3" id="email_container">
                    <label for="register_form_email" class="font-medium">Email</label>
                    <input type="email" v-model="register.email" id="register_form_email" class="border-gray-300 border-2 rounded-xl" 
                           :disabled="showLoadingSpinner" required>
                </div>

                <!-- Password -->
                <div class="flex flex-col my-3" id="password_container">
                    <label for="register_form_password" class="font-medium">Password</label>
                    <input type="password" v-model="register.password" id="register_form_password" minlength="8" class="border-gray-300 border-2 rounded-xl" 
                           :disabled="showLoadingSpinner" @input="comparePasswords()" required>
                </div>

                <!-- Confirm Password -->
                <div class="flex flex-col my-3" id="confirm_password_container">
                    <label for="register_form_confirm_password" class="font-medium">Confirm Password</label>
                    <input type="password" v-model="register.confirmPassword" id="register_form_confirm_password" minlength="3" 
                           class="border-gray-300 border-2 rounded-xl" @input="comparePasswords()" :disabled="showLoadingSpinner" required>
                </div>

                <!-- Button -->
                <div class="flex flex-col my-6 justify-center items-center" id="submit_container">
                    <input type="submit" name="send" id="register_form_submit" value="Register" :disabled="disableRegister || showLoadingSpinner" 
                           class="border-gray-300 border-2 rounded-md py-2 w-1/3 cursor-pointer disabled:opacity-30">
                    <p class="mt-5">Already registered? <span class="underline cursor-pointer py-2" @click="!showLoadingSpinner && emit('showLoginFormEvent', false)">Login now!</span></p>
                </div>
            </form>
        </div>
    </div>
</template>
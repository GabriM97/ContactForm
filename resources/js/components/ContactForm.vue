<script setup>
    import axios from 'axios';
    import { ref, reactive, onMounted } from 'vue';
    import Spinner from './Spinner.vue';
    
    const emit = defineEmits('contactSentEvent', 'errorEvent');

    const props = defineProps({
        userEmail: String
    });

    const contact = reactive({
        name: '',
        surname: '',
        email: '',
        message: ''
    });

    const messageMaxLenght = 1000;
    const showLoadingSpinner = ref(false);
    const opacityClass = ref('');

    const handleSend = function () {
        // show spinner and disable form
        toggleSpinner();

        axios.post('/api/contacts', {
            name: contact.name,
            surname: contact.surname,
            email: contact.email || props.userEmail,
            message: contact.message
        }).then((res) => {
            emit('contactSentEvent', res.data.data.id)
            clearFormFields();
        }).catch((err) => {
            if (err.response != undefined) {
                emit('errorEvent', err.response.data.error_message);
                return;
            }
            emit('errorEvent', err.message);
        }).finally(() => {
            toggleSpinner();
        });
    }

    const toggleSpinner = function () {
        showLoadingSpinner.value = !showLoadingSpinner.value;
        opacityClass.value = opacityClass.value === '' ? 'opacity-50' : '';
    }

    const clearFormFields = function () {
        contact.name = '';
        contact.surname = '';
        contact.email = '';
        contact.message = '';
    }
</script>

<template>
    <div id="contact_form_container" class="p-5 relative" :class="opacityClass">

        <Spinner v-if="showLoadingSpinner" />

        <h1 class="text-3xl font-bold mb-5">Contact us</h1>

        <form action="#" id="contact_form" class="flex flex-col gap-y-4" @submit.prevent="handleSend" :disabled="showLoadingSpinner">
            <div class="flex flex-col gap-y-4 2xl:flex-row 2xl:gap-x-1" id="name_surname_container">
                <!-- Name -->
                <div class="flex flex-col 2xl:basis-1/2" id="name_container">
                    <label for="contact_form_name" class="font-medium">Name</label>
                    <input type="text" v-model="contact.name" id="contact_form_name" class="border-gray-300 border-2 rounded-xl" 
                            :disabled="showLoadingSpinner" required>
                </div>

                <!-- Surname -->
                <div class="flex flex-col 2xl:basis-1/2" id="surname_container">
                    <label for="contact_form_surname" class="font-medium">Surname</label>
                    <input type="text" v-model="contact.surname" id="contact_form_surname" class="border-gray-300 border-2 rounded-xl" 
                            :disabled="showLoadingSpinner" required>
                </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col" id="email_container">
                <label for="contact_form_email" class="font-medium">Email</label>
                <input type="email" v-model="contact.email" id="contact_form_email" class="border-gray-300 border-2 rounded-xl" 
                        :disabled="showLoadingSpinner" :placeholder="props.userEmail">
            </div>

            <!-- Message -->
            <div class="flex flex-col" id="message_container">
                <label for="contact_form_message" class="font-medium relative">Message 
                    <span class="absolute right-2 bottom-0.5 text-sm">{{ contact.message.length }}/{{ messageMaxLenght }}</span>
                </label>
                <textarea v-model="contact.message" id="contact_form_message" class="border-gray-300 border-2 rounded-xl h-20" 
                            :disabled="showLoadingSpinner" :maxlength="messageMaxLenght" required @change="showRealtimeChars">
                </textarea>
            </div>

            <!-- Buttons -->
            <div class="flex flex-row gap-x-2 sm:gap-x-5 my-3 justify-center items-center" id="submit_container">
                <input type="submit" name="send" id="contact_form_submit"  class="border-gray-300 border-2 rounded-md px-auto py-2 w-2/3 cursor-pointer" 
                        :disabled="showLoadingSpinner" value="Send">
                <input type="reset" name="cancel" id="contact_form_cancel" class="border-gray-300 border-2 rounded-md px-auto py-2 w-1/3 cursor-pointer bg-violet-400 hover:bg-violet-500 active:bg-violet-600 focus:ring"
                        :disabled="showLoadingSpinner" value="Cancel">
            </div>
        </form>
    </div>
</template>
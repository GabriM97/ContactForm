<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    const props = defineProps({
        errorMessage: String,
        userName: String,
    });

    const emit = defineEmits(['errorEvent', 'loggedOutEvent'])

    const handleLogout = function () {
        axios.post('/api/user/logout', {})
            .catch((err) => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message)
            }).finally((res) => {
                emit('loggedOutEvent', true)
            });
    }
</script>

<template>
    <nav class="flex flex-row-reverse gap-x-1 px-5">
        <div v-if="userName !== undefined" class="flex flex-row">
            <!-- Welcome user -->
            <div class="inline mt-1 mr-3">Hello <span class="font-medium">{{ userName }}</span>!</div>

            <!-- Logout button -->
            <input type="button" name="logout" id="logout" value="Logout" @click="handleLogout"
                class="border-gray-300 bg-slate-500 hover:bg-slate-600 active:bg-slate-700 text-white font-medium border-2 rounded-md px-2 py-1 cursor-pointer">
        </div>
        
        <!-- Error messages -->
        <div v-if="props.errorMessage.value != ''" class="grow p-1 bg-red-300 text-gray-900 rounded-md"
            :class="userName === undefined ? 'absolute -bottom-3 right-5 left-5' : ''">
            {{ errorMessage.value }}
        </div>
    </nav>
</template>
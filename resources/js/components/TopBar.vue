<script setup>
    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    const props = defineProps({
        errorMessage: String,
        userName: String,
    });

    const emit = defineEmits(['errorEvent', 'loggedOutEvent'])

    const handleLogout = function () {
        axios.post('/api/user/logout', { validateStatus: status => status < 400 })
            .catch((err) => {
                emit('errorEvent', err.message)
            }).finally((res) => {
                emit('loggedOutEvent', true)
            });
    }
</script>

<template>
    <nav>
        <div v-if="props.errorMessage.value != ''"
                class="pl-1 bg-red-300 text-gray-900 rounded-md"
            >
                {{ errorMessage.value }}
            </div>

        <div class="text-right" v-if="userName !== undefined">
            <!-- Welcome user -->
            <div class="inline mr-3">Hello <span class="font-medium">{{ userName }}</span>!</div>

            <!-- Logout button -->
            <input type="button" name="logout" id="logout" value="Logout" @click="handleLogout"
                class="border-gray-300 border-2 rounded-md px-2 py-1 cursor-pointer">
        </div>
    </nav>
</template>
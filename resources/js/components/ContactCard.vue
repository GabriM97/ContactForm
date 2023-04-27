<script setup>
    import Icon from './Icon.vue';
    import axios from 'axios';
    import { ref, onMounted } from 'vue';

    const emit = defineEmits(['errorEvent', 'contactDeleted', 'contactBookmark', 'contactStar'])

    const props = defineProps({
        contact: Object
    });

    const handleDelete = function () {
        axios.delete('/api/contacts/' + props.contact.id, {})
            .then(res => {
                emit('contactDeleted', res.data.data.id)
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    };

    const handleBookmark = function () {
        axios.post('/api/contacts/' + props.contact.id + '/bookmark', {})
            .then(res => {
                emit('contactBookmark', res.data.data.id)
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    };

    const handleStar = function () {
        axios.post('/api/contacts/' + props.contact.id + '/star', {})
            .then(res => {
                emit('contactStar', res.data.data.id)
            }).catch (err => {
                if (err.response != undefined) {
                    emit('errorEvent', err.response.data.error_message);
                    return;
                }
                emit('errorEvent', err.message);
            });
    };
</script>

<template>
    <div class="flex flex-col min-w-[70%] max-w-[70%] sm:max-w-[50%] sm:min-w-[50%] md:max-w-[40%] md:min-w-[40%] max-h-[50vh] min-h-[50vh]
                lg:max-w-[30%] lg:min-w-[30%] xl:max-w-[20%] xl:min-w-[20%] 2xl:max-w-[15%] 2xl:min-w-[15%] before:border-t-4 before:border-t-orange-400 before:w-[98%] before:rounded-t-md before:m-auto
                shadow-xl shadow-gray-400 transition-all hover:shadow-gray-700 hover:scale-105 outline-offset-2 mb-5">
        
        <!-- Contact Name -->
        <div class="rounded-t-md text-gray-700 bg-gray-300 h-1/6 max-h-[17%] text-center text-xl">
            <div class="font-medium break-words h-full overflow-y-auto m-2">{{ contact.name }} {{ contact.surname }}</div>
        </div>
        
        <!-- Contact Email -->
        <div class="text-gray-700 bg-gray-300 text-center h-1/6 px-2">
            <div class="overflow-x-auto text-gray-500 mt-1">({{ contact.email }})</div>
        </div>

        <!-- Contact Message -->
        <div class="rounded-none text-gray-600 bg-gray-300 min-h-[60%] mt-0.5 p-2 break-words text-justify">
            <div class="overflow-y-auto max-h-full text-ellipsis">
                {{ contact.message }}
            </div>
        </div>

        <div class="text-gray-700 font-medium bg-gray-300 text-right px-2">
            {{ (new Date(contact.created_at)).toLocaleDateString('en-GB', {weekday: 'short', day: 'numeric', month: 'short', year: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit'}) }}
        </div>

        <!-- Contact Buttons -->
        <div class="flex flex-row justify-between rounded-b-sm text-gray-300 from-sky-700 to-sky-400 bg-gradient-to-r p-2 font-bold">
            <button id="bookmark" @click="handleBookmark" class="border-white bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer">
                <Icon :iconName="contact.bookmark ? 'bookmark_solid' : 'bookmark'" />
            </button>
            
            <button id="star" @click="handleStar" class="border-white bg-cyan-500 hover:bg-cyan-600 active:bg-cyan-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer">
                <Icon :iconName="contact.star ? 'star_solid' : 'star'" />
            </button>

            <button id="del" @click="handleDelete" class="border-white bg-slate-500 hover:bg-slate-600 active:bg-slate-700 text-white border-2 rounded-md px-2 py-1 cursor-pointer">
                <Icon :iconName="'bin'" />
            </button>
        </div>
    </div>
    
</template>
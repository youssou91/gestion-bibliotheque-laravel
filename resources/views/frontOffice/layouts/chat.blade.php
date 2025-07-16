<div x-data="chatBot()" class="p-4 border rounded shadow">
    <h2 class="text-xl font-bold mb-2">Assistant IA</h2>
    
    <div class="space-y-2 max-h-60 overflow-y-auto">
        <template x-for="msg in messages">
            <div :class="msg.type === 'user' ? 'text-right' : 'text-left'">
                <p class="p-2 rounded" :class="msg.type === 'user' ? 'bg-blue-100' : 'bg-gray-100'" x-text="msg.text"></p>
            </div>
        </template>
    </div>

    <form @submit.prevent="sendMessage">
        <input x-model="userInput" type="text" placeholder="Pose ta question..." class="w-full border p-2 rounded mt-4" />
        <button type="submit" class="mt-2 bg-blue-500 text-white px-4 py-1 rounded">Envoyer</button>
    </form>
</div>

<script>
function chatBot() {
    return {
        userInput: '',
        messages: [],
        sendMessage() {
            const question = this.userInput.trim();
            if (!question) return;

            this.messages.push({ type: 'user', text: question });
            this.userInput = '';

            fetch('/ai/ask', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ question })
            })
            .then(res => res.json())
            .then(data => {
                this.messages.push({ type: 'ai', text: data.response });
            })
            .catch(err => {
                this.messages.push({ type: 'ai', text: 'Erreur de l’IA : ' + err.message });
            });
        }
    }
}   
</script>


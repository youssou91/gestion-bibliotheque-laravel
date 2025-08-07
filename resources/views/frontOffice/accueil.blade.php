@extends('frontOffice.layouts.app')
@section('content')
<body id="reportsPage">
    <div id="home">
        <div class="row">
            <div class="col-12">
                <div class="container mt-5">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                        </div>
                    @endif
                    
                    <!-- Bouton IA fixe en bas à droite -->
                    {{-- <div class="position-fixed bottom-4 end-4 z-3">
                        <button type="button" class="btn btn-info btn-lg rounded shadow-lg" 
                                data-bs-toggle="modal" data-bs-target="#modalChatbot"
                                title="Assistant Libra-IA">
                            <i class="fas fa-robot"></i>
                        </button>
                    </div> --}}
                    
                    <div class="row">
                        @foreach ($ouvrages as $livre)
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 shadow-sm border-0 rounded">
                                    <img src="{{ asset('assets/img/' . $livre->photo) }}" class="card-img-top" alt="{{ $livre->titre }}" style="height: 350px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $livre->titre }}</h5>
                                        <p class="card-text">
                                            <small>Par {{ $livre->auteur }}</small><br>
                                            <strong>{{ $livre->stock && $livre->stock->quantite > 0 ? 'Disponible' : 'Indisponible' }}</strong>
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between gap-3 mt-3 flex-wrap">
                                            <form action="{{ route('frontOffice.emprunts.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="livre_id" value="{{ $livre->id }}">
                                                <button type="submit" class="btn btn-sm btn-success border-0 rounded" title="Emprunter ce livre" {{ !$livre->stock || $livre->stock->quantite <= 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-cart-plus fs-5"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('frontOffice.reservations.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="ouvrage_id" value="{{ $livre->id }}">
                                                <button type="submit" class="btn btn-sm btn-warning border-0 rounded" title="Réserver ce livre" {{ !$livre->stock || $livre->stock->quantite <= 0 ? 'disabled' : '' }}>
                                                    <i class="fas fa-calendar-check fs-5"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('frontOffice.favoris.ajouter', $livre->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded" title="Ajouter aux favoris">
                                                    <i class="fas fa-heart fs-5"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-outline-primary border-0 rounded" title="Aperçu commentaires" data-bs-toggle="modal" data-bs-target="#modalCommentaires{{ $livre->id }}">
                                                <i class="fas fa-eye fs-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Commentaires -->
                            <div class="modal fade" id="modalCommentaires{{ $livre->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $livre->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalLabel{{ $livre->id }}">
                                                {{ $livre->titre }} — Commentaires
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Auteur du livre:</strong> {{ $livre->auteur }}</p>
                                            <p><strong>Description:</strong>
                                                {{ $livre->description ?? 'Aucune description disponible.' }}
                                            </p>
                                            <div class="mb-4">
                                                @php
                                                    $commentairesApprouves = $livre->commentaires->where('statut', 'approuve');
                                                @endphp
                                                <h5>Commentaires ({{ $commentairesApprouves->count() }})</h5>
                                                <div class="comments-section" style="max-height: 300px; overflow-y: auto;">
                                                    @forelse($commentairesApprouves as $commentaire)
                                                        <div class="card mb-3">
                                                            <div class="card-body">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div>
                                                                        <div class="d-flex align-items-center mb-2">
                                                                            <div class="me-2" style="width: 40px; height: 40px; background-color: #f0f0f0; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                                                <i class="fas fa-user"></i>
                                                                            </div>
                                                                            <div>
                                                                                <strong>{{ $commentaire->utilisateur->nom }} {{ $commentaire->utilisateur->prenom }}</strong>
                                                                                <div class="text-warning">
                                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                                        <i class="fas fa-star{{ $i > $commentaire->note ? '-empty' : '' }}"></i>
                                                                                    @endfor
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <p class="mb-0">
                                                                            @php
                                                                                $words = str_word_count($commentaire->contenu, 1);
                                                                                $preview = implode(' ', array_slice($words, 0, 10));
                                                                                echo $preview.(count($words) > 10 ? '...' : '');
                                                                            @endphp
                                                                        </p>
                                                                    </div>
                                                                    <small class="text-muted-text-end">
                                                                        {{ $commentaire->created_at->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                                                @if (str_word_count($commentaire->contenu) > 10)
                                                                    <button class="btn btn-sm btn-link p-0 mt-2" data-bs-toggle="collapse" data-bs-target="#commentFull{{ $commentaire->id }}">
                                                                        Voir plus
                                                                    </button>
                                                                    <div id="commentFull{{ $commentaire->id }}" class="collapse mt-2">
                                                                        {{ $commentaire->contenu }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <div class="alert alert-info">Aucun commentaire approuvé pour le moment.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <hr>
                                            <h5>Ajouter un commentaire</h5>
                                            <form method="POST" action="{{ route('frontOffice.ouvrages.commenter', $livre->id) }}">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="commentaire{{ $livre->id }}" class="form-label">Votre commentaire :</label>
                                                    <textarea name="commentaire" id="commentaire{{ $livre->id }}" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="note{{ $livre->id }}" class="form-label">Note (1 à 5) :</label>
                                                    <select name="note" id="note{{ $livre->id }}" class="form-select" required>
                                                        <option selected disabled value="">---Sélectionnez une note---</option>
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <option value="{{ $i }}">{{ $i }} étoile{{ $i > 1 ? 's' : '' }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Envoyer</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $ouvrages->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Chatbot -->
    <div class="modal fade" id="modalChatbot" tabindex="-1" aria-labelledby="chatbotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="chatbotModalLabel">Libra-IA - Assistant Bibliothèque</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="min-height: 400px;">
                    <div id="chatbotMessages" class="mb-3" style="height: 300px; overflow-y: auto;">
                        <div class="alert alert-info">
                            <strong>Libra-IA:</strong> Bonjour ! Je suis votre assistant bibliothèque. Posez-moi vos questions sur nos ouvrages ou services.
                        </div>
                    </div>
                    <div class="input-group">
                        <input type="text" id="chatbotInput" class="form-control" placeholder="Posez votre question..." aria-label="Question">
                        <button class="btn btn-primary" type="button" id="sendChatbotMessage">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2">Exemple: "Résumez un livre", "Trouvez des livres sur la cuisine", etc.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" id="clearChat">Effacer la conversation</button>
                </div>
            </div>
        </div>
    </div>

    <!-- @push('scripts') -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messagesDiv = document.getElementById('chatbotMessages');
            const inputField = document.getElementById('chatbotInput');
            const sendButton = document.getElementById('sendChatbotMessage');
            const clearButton = document.getElementById('clearChat');

            // Fonction d'ajout de message (unique)
            function addMessage(author, text, isBot = false) {
                const div = document.createElement('div');
                div.className = 'mb-2 ' + (isBot ? 'alert alert-info' : 'alert alert-primary text-end');
                div.innerHTML = `<strong>${author}:</strong> ${text}`;
                messagesDiv.appendChild(div);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            // Fonction d'envoi de message
            function sendMessage() {
                const message = inputField.value.trim();
                if (!message) return;

                addMessage('Vous', message);
                inputField.value = '';

                // Loader IA
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'alert alert-secondary mb-2 chatbot-loader';
                loadingDiv.innerHTML = '<strong>Libra-IA:</strong> Libra-IA réfléchit...';
                messagesDiv.appendChild(loadingDiv);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;

                inputField.disabled = true;
                sendButton.disabled = true;

                fetch('/ai/ask', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ question: message })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    loadingDiv.remove();
                    if (data.response) {
                        addMessage('Libra-IA', data.response, true);
                    } else if (data.error) {
                        addMessage('Libra-IA', "<span class='text-danger'>" + data.error + "</span>", true);
                    }
                })
                .catch(function() {
                    loadingDiv.remove();
                    addMessage('Libra-IA', "<span class='text-danger'>Erreur de connexion au serveur.</span>", true);
                })
                .finally(function() {
                    inputField.disabled = false;
                    sendButton.disabled = false;
                    inputField.focus();
                });
            }

            // Entrée clavier
            inputField.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
            sendButton.onclick = sendMessage;

            // Effacer la conversation
            clearButton.onclick = function() {
                messagesDiv.innerHTML = '';
                addMessage('Libra-IA', "Bonjour ! Je suis votre assistant bibliothèque. Posez-moi vos questions sur nos ouvrages ou services.", true);
            };
            // Événements
            sendButton.addEventListener('click', sendMessage);
            inputField.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            });
            
            clearButton.addEventListener('click', function() {
                messagesDiv.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Libra-IA:</strong> Bonjour ! Je suis votre assistant bibliothèque. Posez-moi vos questions sur nos ouvrages ou services.
                    </div>
                `;
            });
            
            // Réinitialiser lors de la fermeture du modal
            document.getElementById('modalChatbot').addEventListener('hidden.bs.modal', function() {
                messagesDiv.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Libra-IA:</strong> Bonjour ! Je suis votre assistant bibliothèque. Posez-moi vos questions sur nos ouvrages ou services.
                    </div>
                `;
            });
        });
    </script>
    @endpush
</body>
@endsection
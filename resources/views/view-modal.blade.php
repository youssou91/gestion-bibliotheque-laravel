<div class="modal fade" id="viewStockModal-{{ $stock->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box-open mr-2"></i>
                    Détails du Stock - #STK-{{ str_pad($stock->id, 4, '0', STR_PAD_LEFT) }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Section Ouvrage -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-book mr-2"></i>Ouvrage</h6>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    @if($stock->ouvrage->photo)
                                        <img src="{{ asset('assets/img/' . $stock->ouvrage->photo) }}" 
                                             class="book-cover-lg mb-3" alt="Couverture">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                             style="height: 150px; width: 100%;">
                                            <i class="fas fa-book-open fa-3x text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <h4>{{ $stock->ouvrage->titre }}</h4>
                                <p class="text-muted">{{ $stock->ouvrage->auteur }}</p>
                                
                                <ul class="list-group list-group-flush mt-3">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-barcode mr-2"></i>ISBN</span>
                                        <span>{{ $stock->ouvrage->isbn ?? 'Non renseigné' }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-calendar-alt mr-2"></i>Année</span>
                                        <span>{{ $stock->ouvrage->annee_publication ?? 'Non renseigné' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Section Détails Stock -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle mr-2"></i>Détails du Stock</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-hashtag mr-2"></i>ID Stock</span>
                                        <span>#STK-{{ str_pad($stock->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-boxes mr-2"></i>Quantité</span>
                                        <span>{{ $stock->quantite }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-tag mr-2"></i>Statut</span>
                                        <span class="badge badge-{{ 
                                            $stock->statut === 'En stock' ? 'en-stock' : 
                                            ($stock->statut === 'Stock faible' ? 'stock-faible' : 'rupture') 
                                        }}">
                                            <i class="fas {{ 
                                                $stock->statut === 'En stock' ? 'fa-check-circle' : 
                                                ($stock->statut === 'Stock faible' ? 'fa-exclamation-triangle' : 'fa-times-circle') 
                                            }} mr-1"></i> 
                                            {{ $stock->statut }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-money-bill-wave mr-2"></i>Prix Achat</span>
                                        <span>{{ number_format($stock->prix_achat, 2) }} $</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-tags mr-2"></i>Prix Vente</span>
                                        <span>{{ number_format($stock->prix_vente, 2) }} $</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-calendar-plus mr-2"></i>Date création</span>
                                        <span>{{ $stock->created_at->format('d/m/Y') }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>
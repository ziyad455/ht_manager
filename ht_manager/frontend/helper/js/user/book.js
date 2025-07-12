// Room Filter System
class RoomFilter {
    constructor() {
        this.rooms = [];
        this.currentFilters = {
            type: 'all',
            superficie: 'all'
        };
        this.init();
    }

    init() {
        this.setupRoomData();
        this.createFilterUI();
        this.bindEvents();
        this.updateRoomCount();
    }

    setupRoomData() {
        // Define room data with types and superficie
        this.rooms = [
            {
                id: 'room-1',
                name: 'Deluxe Ocean View',
                type: 'double',
                superficie: 'moyenne',
                element: null
            },
            {
                id: 'room-2',
                name: 'Executive Suite',
                type: 'suite',
                superficie: 'grande',
                element: null
            },
            {
                id: 'room-3',
                name: 'Presidential Villa',
                type: 'suite',
                superficie: 'grande',
                element: null
            },
            {
                id: 'room-4',
                name: 'Romantic Getaway',
                type: 'double',
                superficie: 'moyenne',
                element: null
            },
            {
                id: 'room-5',
                name: 'Garden Paradise',
                type: 'simple',
                superficie: 'petite',
                element: null
            },
            {
                id: 'room-6',
                name: 'Wellness Retreat',
                type: 'familiale',
                superficie: 'grande',
                element: null
            }
        ];

        // Get room elements and assign them
        const roomCards = document.querySelectorAll('.room-card');
        roomCards.forEach((card, index) => {
            if (this.rooms[index]) {
                this.rooms[index].element = card;
                card.setAttribute('data-room-id', this.rooms[index].id);
                card.setAttribute('data-type', this.rooms[index].type);
                card.setAttribute('data-superficie', this.rooms[index].superficie);
            }
        });
    }

    createFilterUI() {
        const filterHTML = `
            <div class="filter-section p-6 mb-8 shadow-lg">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-filter mr-2"></i>Filter Rooms
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Room Type Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Room Type</label>
                        <div class="filter-options">
                            <button class="filter-btn active" data-filter="type" data-value="all">
                                <i class="fas fa-th mr-1"></i>All Types
                            </button>
                            <button class="filter-btn" data-filter="type" data-value="simple">
                                <i class="fas fa-bed mr-1"></i>Simple
                            </button>
                            <button class="filter-btn" data-filter="type" data-value="double">
                                <i class="fas fa-bed mr-1"></i>Double
                            </button>
                            <button class="filter-btn" data-filter="type" data-value="suite">
                                <i class="fas fa-crown mr-1"></i>Suite
                            </button>
                            <button class="filter-btn" data-filter="type" data-value="familiale">
                                <i class="fas fa-users mr-1"></i>Familiale
                            </button>
                        </div>
                    </div>

                    <!-- Surface Area Filter -->
                    <div class="filter-group">
                        <label class="filter-label">Surface Area</label>
                        <div class="filter-options">
                            <button class="filter-btn active" data-filter="superficie" data-value="all">
                                <i class="fas fa-expand mr-1"></i>All Sizes
                            </button>
                            <button class="filter-btn" data-filter="superficie" data-value="petite">
                                <i class="fas fa-compress mr-1"></i>Petite
                            </button>
                            <button class="filter-btn" data-filter="superficie" data-value="moyenne">
                                <i class="fas fa-expand-arrows-alt mr-1"></i>Moyenne
                            </button>
                            <button class="filter-btn" data-filter="superficie" data-value="grande">
                                <i class="fas fa-expand mr-1"></i>Grande
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-4">
                    <button class="clear-filters-btn">
                        <i class="fas fa-times mr-1"></i>Clear All Filters
                    </button>
                    <div class="room-count" id="room-count">
                        Showing all rooms
                    </div>
                </div>
            </div>
        `;

        // Insert filter UI before the room gallery
        const roomGallery = document.querySelector('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-3');
        if (roomGallery) {
            roomGallery.insertAdjacentHTML('beforebegin', filterHTML);
        }
    }

    bindEvents() {
        // Filter button events
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('filter-btn') || e.target.closest('.filter-btn')) {
                const btn = e.target.classList.contains('filter-btn') ? e.target : e.target.closest('.filter-btn');
                this.handleFilterClick(btn);
            }
        });

        // Clear filters event
        const clearBtn = document.querySelector('.clear-filters-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearAllFilters());
        }
    }

    handleFilterClick(btn) {
        const filterType = btn.getAttribute('data-filter');
        const filterValue = btn.getAttribute('data-value');

        // Remove active class from siblings
        const siblings = btn.parentElement.querySelectorAll('.filter-btn');
        siblings.forEach(sibling => sibling.classList.remove('active'));

        // Add active class to clicked button
        btn.classList.add('active');

        // Update current filters
        this.currentFilters[filterType] = filterValue;

        // Apply filters
        this.applyFilters();
    }

    applyFilters() {
        let visibleCount = 0;

        this.rooms.forEach(room => {
            if (!room.element) return;

            const typeMatch = this.currentFilters.type === 'all' || room.type === this.currentFilters.type;
            const superficieMatch = this.currentFilters.superficie === 'all' || room.superficie === this.currentFilters.superficie;

            if (typeMatch && superficieMatch) {
                room.element.classList.remove('filter-hidden');
                room.element.classList.remove('filtering');
                visibleCount++;
            } else {
                room.element.classList.add('filtering');
                setTimeout(() => {
                    room.element.classList.add('filter-hidden');
                    room.element.classList.remove('filtering');
                }, 300);
            }
        });

        this.updateRoomCount(visibleCount);
    }

    updateRoomCount(count = null) {
        const roomCountEl = document.getElementById('room-count');
        if (!roomCountEl) return;

        if (count === null) {
            count = this.rooms.filter(room => room.element && !room.element.classList.contains('filter-hidden')).length;
        }

        if (count === this.rooms.length) {
            roomCountEl.innerHTML = '<i class="fas fa-home mr-1"></i>Showing all rooms';
        } else {
            roomCountEl.innerHTML = `<i class="fas fa-home mr-1"></i>Showing ${count} of ${this.rooms.length} rooms`;
        }
    }

    clearAllFilters() {
        // Reset filters
        this.currentFilters = {
            type: 'all',
            superficie: 'all'
        };

        // Reset button states
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-value') === 'all') {
                btn.classList.add('active');
            }
        });

        // Show all rooms
        this.rooms.forEach(room => {
            if (room.element) {
                room.element.classList.remove('filter-hidden', 'filtering');
            }
        });

        this.updateRoomCount();
    }
}

// Initialize the filter system when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        new RoomFilter();
    });
} else {
    new RoomFilter();
}
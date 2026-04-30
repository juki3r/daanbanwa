<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="h4 mb-0">News Management</h2>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addNewsModal">
                Add News
            </button>
        </div>
    </x-slot>


    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <div class="container py-4">
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- SEARCH -->
                <div class="mb-3">
                    <input type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Search news items...">
                </div>

                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle" id="newsTable">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Published At</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($news as $news_item)
                                <tr>
                                    <td>{{ $news_item->id }}</td>
                                    <td class="text-capitalize">{{ $news_item->title }}</td>
                                    <td>{{ $news_item->content }}</td>
                                    <td>{{ $news_item->published_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- ADD NEWS MODAL -->
    <div class="modal fade" id="addNewsModal" tabindex="-1" aria-labelledby="addNewsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('news.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="addNewsModalLabel">Add News</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Content</label>
                            <textarea name="content" class="form-control" required></textarea>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Save News
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // LIVE SEARCH
        document.getElementById('searchInput').addEventListener('keyup', function () {
            let value = this.value.toLowerCase();
            let rows = document.querySelectorAll("#newsTable tbody tr");

            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
            });
        });
    </script>
</x-app-layout>
@section('title', 'Chat page')
<?php $img_src = "https://lh4.googleusercontent.com/proxy/Jc8H_cE_iojJu0D19oVA9xR6NlpUURKGnaCO35qvRtVdHh-H-jWeD6pqbXnQMBDSQfIMxh_txjX-pVJiUvCToQCHSEAYN2s3Ta7kxFd2GzUpqc-j8CfuuS_dOjkXclS5VBFQK3bA9rjgvRc_tGSF6FKSlWi6" ?>
<div class="row gy-2 mt-4">
    <!-- Left chat -->
    <div class="col-lg-3 col-md-4">
        <div class="card">
            <div class="card-header">
                <div class="flex-grow-1 input-group input-group-merge rounded-pill ms-1">
                    <input class="form-control py-2 border-0 shadow-none" wire:model.live="search_user_list" placeholder="Type your message here..." />
                </div>
            </div>
            <div class="card-body px-2" style="background-color: #f5f5f982">
                <ul class="list-group" id="list-users-group" style="height: 500px;overflow-y: auto">
                    @forelse ($users as $user)
                        <li class="cursor-pointer list-group-item d-flex justify-content-between align-items-start {{ $this->chat_user_active == $user->name ? 'active' : '' }}" wire:click="get_conversation({{ $user->id }})" onclick="scrolling_chat()">
                            <div class="d-flex gap-2 align-items-start">
                                <div class="user-logo">
                                    <img src="https://www.asirox.com/wp-content/uploads/2022/07/pngtree-user-vector-avatar-png-image_1541962.jpeg" class="user_chat_logo">
                                </div>
                                <div class="user-chat-name">
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <div class="d-flex gap-1 align-items-center">
                                        @if ($user->is_online == 1)
                                            <i class="fa-solid fa-circle text-success" style="font-size: 10px"></i>
                                            <span class="mb-1">Online</span>
                                        @else
                                            <i class="fa-solid fa-circle text-danger" style="font-size: 10px"></i>
                                            <span class="mb-1">Offline</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                          {{-- <span class="badge bg-success rounded-pill">1</span> --}}
                        </li>
                        @empty
                        <p class="px-2">No users available.</p>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    <!-- Right chat -->
    <div class="col-lg-9 col-md-8">
        <div class="card">

            {{-- Header conversation  --}}
            @if ($this->chat_user_active !="" && $this->conversation != null )
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2 align-items-start">
                            <div class="user-logo">
                                <img src="https://www.asirox.com/wp-content/uploads/2022/07/pngtree-user-vector-avatar-png-image_1541962.jpeg" class="user_chat_logo">
                            </div>
                            <div class="user-chat-name">
                                <h6 class="mb-0" id="user_conversation_active">{{ $this->chat_user_active }}</h6>
                                {{-- <span class="text-muted">Last seen at 14:20</span> --}}
                                <div class="d-flex gap-1 align-items-center">
                                    @if ($this->chat_user->is_online == 1)
                                        <i class="fa-solid fa-circle text-success" style="font-size: 10px"></i>
                                        <span class="mb-1">Online</span>
                                    @else
                                        <i class="fa-solid fa-circle text-danger" style="font-size: 10px"></i>
                                        <span class="mb-1">Offline</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-danger" wire:click="removeChat">
                            <i class="fa fa-xmark"></i>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Conversation body --}}
            <div class="card-body" style="background-color: #f5f5f982">mailing
                <div class="chat-area tab-content d-flex gap-2 flex-column py-4 px-2" style="height: 500px;overflow-y: auto;" id="tabContent" wire:poll>
                    @forelse ($messages as $message)
                        <div class="d-flex {{ $message->user_id == Auth::user()->id ? 'flex-row-reverse' : '' }} gap-2 align-items-start">
                            <div class="user-logo">
                                <img src="https://www.asirox.com/wp-content/uploads/2022/07/pngtree-user-vector-avatar-png-image_1541962.jpeg" class="rounded" width="30" height="30">
                            </div>
                            <div class="mb-3">
                                <div class="py-1 px-2 mb-1 alert text-start {{ $message->user_id == Auth::user()->id ? 'alert-info' : 'alert-light' }}">
                                    {{-- <img data-bs-toggle="modal" data-bs-target="#PreviewImageChat" onclick="previewImage( '{{$img_src }}')" src="{{ $img_src }}" class="rounded-1" width="300">
                                    <!-- Modal -->
                                    <div class="modal fade" id="PreviewImageChat" tabindex="-1" aria-labelledby="PreviewImageChatLabel" aria-hidden="true" wire:ignore>
                                        <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body p-0">
                                                <img src="{{ $img_src }}" class="rounded-1" width="100%">
                                            </div>
                                        </div>
                                        </div>
                                    </div> --}}
                                    <span class="text-muted">{{ $message->content }}</span>
                                </div>
                                <span class="text-muted d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-clock" style="font-size: 10px"></i>
                                    <span style="font-size: 11px">{{ $createdDate = Carbon\Carbon::parse($message->created_at)->format('l, F j, Y - h:i A')}}</span>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="d-flex flex-column gap-4 justify-content-center align-items-center h-100">
                            <i class="fa-solid fa-comment-slash" style="font-size: 100px"></i>
                            <h4>Please select user to start chat</h4>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Footer input to send message --}}
            @if ($this->chat_user_active != '' && $this->conversation != null)
                <div class="card-footer text-body-secondary">
                    <form wire:submit ="send_message">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <input class="form-control py-2 border-0 shadow-none" id="message_user"
                                wire:model="message_user" placeholder="Type your message here...">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-primary d-flex" id="send_message" onclick="scrolling_chat()">
                                    <span class="align-middle d-md-inline-block d-none">Send</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function scrolling_chat(){
        document.getElementById('tabContent').scrollTop =  document.getElementById('tabContent').scrollHeight
    }
    document.addEventListener("scrolling_chat",()=>{
        scrolling_chat()
    })
    // window.addEventListener("load",()=>{
    // })
    function previewImage(img_src){
        console.log(img_src);
    }
</script>

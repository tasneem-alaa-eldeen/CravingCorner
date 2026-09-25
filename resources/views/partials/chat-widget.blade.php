@auth
<div x-data="{ chatOpen: false }" x-cloak style="position:fixed; bottom:1.5rem; right:1.5rem; z-index:100;">

    <!-- Toggle button -->
    <button @click="chatOpen = ! chatOpen"
            style="width:56px; height:56px; border-radius:999px; background: var(--cc-mustard); border:none; cursor:pointer; box-shadow:0 10px 26px rgba(226,154,46,0.4); font-size:1.4rem; display:flex; align-items:center; justify-content:center;">
        <span x-show="!chatOpen">💬</span>
        <span x-show="chatOpen" style="display:none;">✕</span>
    </button>

    <!-- Chat panel -->
    <div x-show="chatOpen" x-transition style="display:none; position:absolute; bottom:70px; right:0; width:320px; max-width:88vw; background: var(--cc-surface); border:1px solid var(--cc-line); border-radius:16px; box-shadow:0 20px 44px rgba(43,27,16,0.25); overflow:hidden;">

        <div style="background: var(--cc-ink); color: var(--cc-paper); padding:0.75rem 1rem; font-weight:700; font-family:'Baloo 2',sans-serif; display:flex; justify-content:space-between; align-items:center;">
            <span>{{ auth()->user()->isAdmin() ? 'Admin Assistant' : 'CravingCorner Assistant' }}</span>
            <a href="{{ route('chatbot.index') }}" style="color: var(--cc-mustard); font-size:0.7rem; text-decoration:none;">Full view ↗</a>
        </div>

        <div id="widget-conversation" style="padding:0.85rem; display:flex; flex-direction:column; gap:0.6rem; max-height:280px; overflow-y:auto;">
            <div style="background: var(--cc-paper); border:1px solid var(--cc-line); border-radius:10px; padding:0.5rem 0.75rem; font-size:0.8125rem; max-width:85%;">
                @if (auth()->user()->isAdmin())
                    Ask me about sales, stock, or orders.
                @else
                    Ask me what to eat or drink today.
                @endif
            </div>
        </div>

        <form id="widget-chat-form" style="padding:0.75rem; border-top:1px solid var(--cc-line); display:flex; gap:0.5rem;">
            @csrf
            <input type="text" id="widget-message" required maxlength="500" placeholder="Type a message..." class="input-cc" style="flex:1; font-size:0.8125rem;">
            <button type="submit" class="btn-mustard" style="padding:0.5rem 0.75rem; font-size:0.8125rem;">Go</button>
        </form>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('widget-chat-form');
        const conversation = document.getElementById('widget-conversation');
        const input = document.getElementById('widget-message');
        if (!form) return;

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            const q = document.createElement('div');
            q.style.cssText = 'align-self:flex-end; max-width:85%; border-radius:10px; background:var(--cc-mustard); color:var(--cc-ink); padding:0.5rem 0.75rem; font-size:0.8125rem; font-weight:600;';
            q.textContent = message;
            conversation.append(q);

            const pending = document.createElement('div');
            pending.style.cssText = 'max-width:85%; border-radius:10px; background:var(--cc-paper); border:1px solid var(--cc-line); padding:0.5rem 0.75rem; font-size:0.8125rem; color:var(--cc-text-muted);';
            pending.textContent = 'Thinking...';
            conversation.append(pending);
            conversation.scrollTop = conversation.scrollHeight;

            input.value = '';

            try {
                const response = await fetch('{{ route('chatbot.respond') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ message }),
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message ?? 'The request failed.');

                pending.textContent = result.content;
                pending.style.color = 'var(--cc-ink)';
            } catch (error) {
                pending.textContent = error.message;
                pending.style.color = 'var(--cc-clay)';
            } finally {
                conversation.scrollTop = conversation.scrollHeight;
            }
        });
    })();
</script>
@endauth

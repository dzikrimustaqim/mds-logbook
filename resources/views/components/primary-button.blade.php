<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-mds-primary border-4 border-mds-black font-black text-xs text-white uppercase tracking-widest hover:bg-mds-accent-orange hover:text-mds-black focus:bg-mds-accent-orange active:bg-mds-primary focus:outline-none focus:ring-2 focus:ring-mds-primary focus:ring-offset-2 transition ease-in-out duration-150 shadow-chunky']) }}>
    {{ $slot }}
</button>

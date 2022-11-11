<div>
    <style>
        ul, #myUL {
          list-style-type: none;
        }
        
        #myUL {
          margin: 0;
          padding: 0;
        }
        
        .caret {
          cursor: pointer;
          -webkit-user-select: none; /* Safari 3.1+ */
          -moz-user-select: none; /* Firefox 2+ */
          -ms-user-select: none; /* IE 10+ */
          user-select: none;
        }
        
        .caret::before {
          content: "\25B6";
          color: black;
          display: inline-block;
          margin-right: 6px;
        }
        
        .caret-down::before {
          -ms-transform: rotate(90deg); /* IE 9 */
          -webkit-transform: rotate(90deg); /* Safari */'
          transform: rotate(90deg);  
        }
        
        .nested {
          display: none;
        }
        
        .active {
          display: block;
        }
    </style>
   <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-12">
                @if (session('succes'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('succes') }}
                </div>
                @endif
                <h5>Registrasi Member Baru</h5>
                <div class="row">
                    <div class="col-auto">
                        <input wire:model="new_name" class="form-control @error('new_name') is-invalid @enderror" type="text" placeholder="Inisial Member Baru">
                        @error('new_name')
                        <div class="text-danger text-xs">
                        {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="col-auto">
                        <select wire:model="new_parent" class="form-control" id="exampleFormControlSelect1">
                            <option value="null">pilih parent</option>
                            @foreach ($parents as $parent)
                            <option value="{{$parent->id}}">{{$parent->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button wire:click="register" class="btn btn-primary">Register</button>
                    </div>
                </div>
                <h5 class="mt-5">Perhitungan bonus</h5>
                <div class="row">
                    <div class="col-auto">
                        <select wire:model="select_id" class="form-control" id="exampleFormControlSelect1">
                            <option value="null">pilih member</option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button wire:click="bonusCount" class="btn btn-primary">Hitung</button>
                    </div>
                    <div class="col-auto">
                        @if ($this->bonus)
                        jumlah bonus =    ${{$this->bonus}}
                        @else
                        jumlah bonus = $0
                        @endif
                    </div>
                </div>
                <h5 class="mt-5">Pindah Parent</h5>
                <div class="row">
                    <div class="col-auto">
                        <select wire:model="child_id" class="form-control" id="exampleFormControlSelect1">
                            <option value="null">pilih member</option>
                            @foreach ($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select wire:model="parent_id" class="form-control" id="exampleFormControlSelect1" {{$child_id ? '' : 'disabled'}}>
                            <option value="null">pilih parent baru</option>
                            @foreach ($users as $user)
                            @if ($user->id != $child_id)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button wire:click="migrate" class="btn btn-primary" {{$parent_id == null || $child_id == null ? 'disabled' : ''}}>Migrate</button>
                    </div>
                </div>
                <h5 class="mt-5">Tree View</h5>

                <ul id="myUL">
                    @component('components.nested',[
                        'datas' => $trees
                    ])
                    @endcomponent
                </ul>
            </div>
        </div>
   </div>
   <script>
        var toggler = document.getElementsByClassName("caret");
        var i;
        
        for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
        }
    </script>
</div>

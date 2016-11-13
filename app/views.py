from django.shortcuts import render

# Create your views here. # This view usually known as the controller -> wtf

def index(request):
	return render(request, 'views/index.html', {})
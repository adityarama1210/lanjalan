from django.shortcuts import render
from django.http import HttpResponse

# Create your views here. # This view usually known as the controller -> wtf

def index(request):
	return render(request, 'views/index.html', {})


def search(request, query):
	# to do implement this
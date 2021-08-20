public class ArticlesController : ApiController
    {
        private Storage db = new Storage();

        // GET api/Articles
		public ODataResult<Article> GetArticles(ODataQueryOptions options)
		{
			var items = db.Articles;
			var count = items.Count();
            var res = (IEnumerable<Article>)options.ApplyTo(items);

			return new ODataResult<Article>(res, null, count);
		}

        // GET api/Articles/5
        public Article GetArticle(int id)
        {
            Article article = db.Articles.Find(id);
            if (article == null)
            {
                throw new HttpResponseException(Request.CreateResponse(HttpStatusCode.NotFound));
            }

            return article;
        }

        // PUT api/Articles/5
        public HttpResponseMessage PutArticle(int id, Article article)
        {
            if (ModelState.IsValid && id == article.ID)
            {
                db.Entry(article).State = EntityState.Modified;

                try
                {
                    db.SaveChanges();
                }
                catch (DbUpdateConcurrencyException)
                {
                    return Request.CreateResponse(HttpStatusCode.NotFound);
                }

                return Request.CreateResponse(HttpStatusCode.OK);
            }
            else
            {
                return Request.CreateResponse(HttpStatusCode.BadRequest);
            }
        }

        // POST api/Articles
        public HttpResponseMessage PostArticle(Article article)
        {
            if (ModelState.IsValid)
            {
                db.Articles.Add(article);
                db.SaveChanges();

                HttpResponseMessage response = Request.CreateResponse(HttpStatusCode.Created, article);
                response.Headers.Location = new Uri(Url.Link("DefaultApi", new { id = article.ID }));
                return response;
            }
            else
            {
                return Request.CreateResponse(HttpStatusCode.BadRequest);
            }
        }

        // DELETE api/Articles/5
        public HttpResponseMessage DeleteArticle(int id)
        {
            Article article = db.Articles.Find(id);
            if (article == null)
            {
                return Request.CreateResponse(HttpStatusCode.NotFound);
            }

            db.Articles.Remove(article);

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                return Request.CreateResponse(HttpStatusCode.NotFound);
            }

            return Request.CreateResponse(HttpStatusCode.OK, article);
        }
    }
